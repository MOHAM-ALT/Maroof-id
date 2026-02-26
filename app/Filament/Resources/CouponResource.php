<?php

namespace App\Filament\Resources;

use App\Enums\DiscountType;
use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'الكوبونات';
    protected static \UnitEnum|string|null $navigationGroup = 'إدارة المحتوى';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الكوبون')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('code')
                                ->label('رمز الكوبون')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->placeholder('مثال: SUMMER50')
                                ->columnSpan(2),

                            Select::make('discount_type')
                                ->label('نوع الخصم')
                                ->options(DiscountType::options())
                                ->default(DiscountType::Percentage->value)
                                ->required()
                                ->live(),

                            TextInput::make('discount_value')
                                ->label('قيمة الخصم')
                                ->numeric()
                                ->required()
                                ->suffix(fn ($get) => $get('discount_type') === 'percentage' ? '%' : 'ر.س'),

                            TextInput::make('min_order_amount')
                                ->label('الحد الأدنى للطلب')
                                ->numeric()
                                ->prefix('ر.س')
                                ->helperText('اترك فارغاً إذا لم يكن هناك حد أدنى'),

                            TextInput::make('max_uses')
                                ->label('الحد الأقصى للاستخدام')
                                ->numeric()
                                ->helperText('اترك فارغاً للاستخدام غير المحدود'),

                            TextInput::make('used_count')
                                ->label('عدد الاستخدامات')
                                ->numeric()
                                ->disabled()
                                ->default(0),

                            DateTimePicker::make('expiry_date')
                                ->label('تاريخ الانتهاء')
                                ->timezone('Asia/Riyadh'),

                            Toggle::make('is_active')
                                ->label('نشط')
                                ->default(true),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('الرمز')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('تم نسخ الرمز!'),

                TextColumn::make('discount_type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state),

                TextColumn::make('discount_value')
                    ->label('الخصم')
                    ->formatStateUsing(fn ($record) =>
                        $record->discount_type === 'percentage'
                            ? $record->discount_value . '%'
                            : number_format($record->discount_value, 2) . ' ر.س'
                    )
                    ->sortable(),

                TextColumn::make('used_count')
                    ->label('الاستخدامات')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                TextColumn::make('max_uses')
                    ->label('الحد الأقصى')
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => $state ?? '∞'),

                TextColumn::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('نشط')
                    ->placeholder('الكل')
                    ->trueLabel('نشط فقط')
                    ->falseLabel('غير نشط فقط'),

                SelectFilter::make('discount_type')
                    ->label('نوع الخصم')
                    ->options(DiscountType::options()),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
