<?php

namespace App\Filament\Resources;

use App\Enums\GeneralStatus;
use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-printer';
    protected static ?string $navigationLabel = 'شركاء الطباعة';
    protected static \UnitEnum|string|null $navigationGroup = 'الشركاء';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الشريك')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('اسم الشريك/المطبعة')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->label('البريد الإلكتروني')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            TextInput::make('phone')
                                ->label('رقم الجوال')
                                ->tel()
                                ->required()
                                ->maxLength(255),

                            TextInput::make('city')
                                ->label('المدينة')
                                ->maxLength(255),

                            Textarea::make('address')
                                ->label('العنوان')
                                ->rows(2)
                                ->columnSpan(2),

                            TextInput::make('commission_rate')
                                ->label('معدل العمولة (ر.س/بطاقة)')
                                ->numeric()
                                ->default(6.00)
                                ->prefix('ر.س')
                                ->helperText('العمولة لكل بطاقة (6-30 ريال حسب المستوى)'),

                            Select::make('status')
                                ->label('الحالة')
                                ->options(GeneralStatus::options())
                                ->default(GeneralStatus::Active->value)
                                ->required(),
                        ]),
                    ]),

                Section::make('المعلومات المصرفية')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('bank_name')
                                ->label('اسم البنك')
                                ->maxLength(255),

                            TextInput::make('account_holder')
                                ->label('اسم صاحب الحساب')
                                ->maxLength(255),

                            TextInput::make('iban')
                                ->label('رقم الآيبان (IBAN)')
                                ->maxLength(255)
                                ->columnSpan(2),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم الشريك')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('phone')
                    ->label('الجوال')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('city')
                    ->label('المدينة')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('commission_rate')
                    ->label('العمولة')
                    ->formatStateUsing(fn ($state) => number_format($state, 2) . ' ر.س')
                    ->sortable(),

                TextColumn::make('orders_count')
                    ->label('الطلبات')
                    ->counts('orders')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn ($state) => $state?->color() ?? 'gray')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state),

                TextColumn::make('created_at')
                    ->label('تاريخ الانضمام')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(GeneralStatus::options()),

                SelectFilter::make('city')
                    ->label('المدينة')
                    ->options(function () {
                        return Partner::whereNotNull('city')
                            ->distinct()
                            ->pluck('city', 'city')
                            ->toArray();
                    }),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),

                Actions\Action::make('toggle_status')
                    ->label('تغيير الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Select::make('status')
                            ->label('الحالة الجديدة')
                            ->options(GeneralStatus::options())
                            ->required(),
                    ])
                    ->action(function (Partner $record, array $data) {
                        $record->update(['status' => $data['status']]);
                    })
                    ->color('warning'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),

                    Actions\BulkAction::make('activate')
                        ->label('تفعيل')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'active'])),

                    Actions\BulkAction::make('deactivate')
                        ->label('تعطيل')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'inactive'])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
