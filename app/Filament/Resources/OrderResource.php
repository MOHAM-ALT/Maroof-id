<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'الطلبات';
    protected static \UnitEnum|string|null $navigationGroup = 'إدارة المحتوى';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الطلب')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('order_number')
                                ->label('رقم الطلب')
                                ->disabled()
                                ->default(fn () => 'MRF-' . strtoupper(uniqid())),

                            Select::make('user_id')
                                ->label('العميل')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->required(),

                            Select::make('card_id')
                                ->label('البطاقة')
                                ->relationship('card', 'title')
                                ->searchable()
                                ->nullable(),

                            Select::make('type')
                                ->label('نوع الطلب')
                                ->options(OrderType::options())
                                ->required()
                                ->default(OrderType::PhysicalCard->value),

                            TextInput::make('quantity')
                                ->label('الكمية')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->required(),
                        ]),
                    ]),

                Section::make('التسعير')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('subtotal')
                                ->label('المجموع الفرعي')
                                ->numeric()
                                ->prefix('ر.س')
                                ->required(),

                            TextInput::make('tax')
                                ->label('الضريبة (15%)')
                                ->numeric()
                                ->prefix('ر.س')
                                ->default(0),

                            TextInput::make('shipping_fee')
                                ->label('رسوم الشحن')
                                ->numeric()
                                ->prefix('ر.س')
                                ->default(0),

                            TextInput::make('discount')
                                ->label('الخصم')
                                ->numeric()
                                ->prefix('ر.س')
                                ->default(0),

                            TextInput::make('total')
                                ->label('الإجمالي')
                                ->numeric()
                                ->prefix('ر.س')
                                ->required(),
                        ]),
                    ]),

                Section::make('الحالة')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->label('حالة الطلب')
                                ->options(OrderStatus::options())
                                ->default(OrderStatus::Pending->value)
                                ->required(),

                            Select::make('payment_status')
                                ->label('حالة الدفع')
                                ->options(PaymentStatus::options())
                                ->default(PaymentStatus::Pending->value)
                                ->required(),

                            Select::make('payment_method')
                                ->label('طريقة الدفع')
                                ->options([
                                    'tap' => 'Tap',
                                    'stc_pay' => 'STC Pay',
                                    'mada' => 'مدى',
                                    'visa' => 'Visa',
                                    'mastercard' => 'Mastercard',
                                ])
                                ->nullable(),

                            Select::make('shipping_status')
                                ->label('حالة الشحن')
                                ->options([
                                    'pending' => 'معلق',
                                    'processing' => 'قيد التجهيز',
                                    'shipped' => 'تم الشحن',
                                    'delivered' => 'تم التوصيل',
                                    'cancelled' => 'ملغي',
                                ])
                                ->default('pending'),
                        ]),
                    ]),

                Section::make('عنوان الشحن')
                    ->schema([
                        Grid::make(2)->schema([
                            Textarea::make('shipping_address')
                                ->label('العنوان')
                                ->rows(2)
                                ->maxLength(500)
                                ->columnSpan(2),

                            TextInput::make('shipping_city')
                                ->label('المدينة')
                                ->maxLength(255),

                            TextInput::make('shipping_postal_code')
                                ->label('الرمز البريدي')
                                ->maxLength(255),

                            TextInput::make('shipping_phone')
                                ->label('رقم الجوال')
                                ->tel()
                                ->maxLength(255),

                            TextInput::make('tracking_number')
                                ->label('رقم التتبع')
                                ->maxLength(255),
                        ]),
                    ]),

                Section::make('ملاحظات')
                    ->schema([
                        Textarea::make('notes')
                            ->label('ملاحظات العميل')
                            ->rows(2),

                        Textarea::make('admin_notes')
                            ->label('ملاحظات الإدارة')
                            ->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->copyable(),

                TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('card.title')
                    ->label('البطاقة')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state),

                TextColumn::make('total')
                    ->label('الإجمالي')
                    ->money('SAR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('حالة الطلب')
                    ->badge()
                    ->color(fn ($state) => $state?->color() ?? 'gray')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state),

                TextColumn::make('payment_status')
                    ->label('الدفع')
                    ->badge()
                    ->color(fn ($state) => $state?->color() ?? 'gray')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state),

                TextColumn::make('created_at')
                    ->label('تاريخ الطلب')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('حالة الطلب')
                    ->options([
                        'pending' => 'معلق',
                        'confirmed' => 'مؤكد',
                        'processing' => 'قيد المعالجة',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                    ]),

                SelectFilter::make('payment_status')
                    ->label('حالة الدفع')
                    ->options([
                        'pending' => 'معلق',
                        'paid' => 'مدفوع',
                        'failed' => 'فشل',
                        'refunded' => 'مسترجع',
                    ]),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('من تاريخ'),
                        DatePicker::make('created_until')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Actions\EditAction::make(),

                Actions\Action::make('change_status')
                    ->label('تغيير الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Select::make('status')
                            ->label('الحالة الجديدة')
                            ->options([
                                'pending' => 'معلق',
                                'confirmed' => 'مؤكد',
                                'processing' => 'قيد المعالجة',
                                'completed' => 'مكتمل',
                                'cancelled' => 'ملغي',
                            ])
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update(['status' => $data['status']]);
                    })
                    ->color('warning'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
