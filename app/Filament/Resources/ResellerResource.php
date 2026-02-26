<?php

namespace App\Filament\Resources;

use App\Enums\GeneralStatus;
use App\Filament\Resources\ResellerResource\Pages;
use App\Models\Reseller;
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

class ResellerResource extends Resource
{
    protected static ?string $model = Reseller::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'الموزعون';
    protected static \UnitEnum|string|null $navigationGroup = 'الشركاء';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الموزع')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('user_id')
                                ->label('المستخدم')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->required(),

                            TextInput::make('store_name')
                                ->label('اسم المتجر')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('phone')
                                ->label('رقم الجوال')
                                ->tel()
                                ->required()
                                ->maxLength(255),

                            TextInput::make('city')
                                ->label('المدينة')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('commission_rate')
                                ->label('معدل العمولة (%)')
                                ->numeric()
                                ->default(10.00)
                                ->suffix('%'),

                            TextInput::make('stock_alert_level')
                                ->label('حد تنبيه المخزون')
                                ->numeric()
                                ->default(5)
                                ->helperText('تنبيه عندما يقل المخزون عن هذا العدد'),

                            Select::make('status')
                                ->label('الحالة')
                                ->options(GeneralStatus::options())
                                ->default(GeneralStatus::Active->value)
                                ->required(),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('store_name')
                    ->label('اسم المتجر')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city')
                    ->label('المدينة')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('الجوال')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('commission_rate')
                    ->label('العمولة')
                    ->formatStateUsing(fn ($state) => $state . '%')
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResellers::route('/'),
            'create' => Pages\CreateReseller::route('/create'),
            'edit' => Pages\EditReseller::route('/{record}/edit'),
        ];
    }
}
