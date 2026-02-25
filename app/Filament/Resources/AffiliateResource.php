<?php

namespace App\Filament\Resources;

use App\Enums\GeneralStatus;
use App\Filament\Resources\AffiliateResource\Pages;
use App\Models\Affiliate;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AffiliateResource extends Resource
{
    protected static ?string $model = Affiliate::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'المسوقون';
    protected static \UnitEnum|string|null $navigationGroup = 'الشركاء';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات المسوق')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('user_id')
                                ->label('المستخدم')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->required(),

                            TextInput::make('tracking_id')
                                ->label('معرّف التتبع')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText('رمز فريد لتتبع الإحالات'),

                            TextInput::make('commission_rate')
                                ->label('معدل العمولة (%)')
                                ->numeric()
                                ->default(20.00)
                                ->suffix('%'),

                            Select::make('status')
                                ->label('الحالة')
                                ->options(GeneralStatus::options())
                                ->default(GeneralStatus::Active->value)
                                ->required(),

                            TextInput::make('clicks_count')
                                ->label('عدد النقرات')
                                ->numeric()
                                ->disabled()
                                ->default(0),

                            TextInput::make('conversions_count')
                                ->label('عدد التحويلات')
                                ->numeric()
                                ->disabled()
                                ->default(0),

                            TextInput::make('earnings')
                                ->label('الأرباح الإجمالية')
                                ->numeric()
                                ->disabled()
                                ->prefix('ر.س')
                                ->default(0),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('المسوق')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('tracking_id')
                    ->label('معرّف التتبع')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('تم نسخ الرمز!'),

                TextColumn::make('clicks_count')
                    ->label('النقرات')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                TextColumn::make('conversions_count')
                    ->label('التحويلات')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('success'),

                TextColumn::make('commission_rate')
                    ->label('العمولة')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable(),

                TextColumn::make('earnings')
                    ->label('الأرباح')
                    ->money('SAR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn ($state) => $state?->color() ?? 'gray')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(GeneralStatus::options()),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('earnings', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAffiliates::route('/'),
            'create' => Pages\CreateAffiliate::route('/create'),
            'edit' => Pages\EditAffiliate::route('/{record}/edit'),
        ];
    }
}
