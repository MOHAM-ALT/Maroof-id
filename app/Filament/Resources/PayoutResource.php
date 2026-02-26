<?php

namespace App\Filament\Resources;

use App\Enums\PayoutStatus;
use App\Filament\Resources\PayoutResource\Pages;
use App\Models\Payout;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PayoutResource extends Resource
{
    protected static ?string $model = Payout::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'التحويلات المالية';
    protected static \UnitEnum|string|null $navigationGroup = 'الشركاء';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات التحويل')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('user_id')
                                ->label('المستخدم')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->required(),

                            TextInput::make('amount')
                                ->label('المبلغ')
                                ->numeric()
                                ->required()
                                ->prefix('ر.س'),

                            Select::make('method')
                                ->label('طريقة الدفع')
                                ->options([
                                    'bank_transfer' => 'تحويل بنكي',
                                    'wallet' => 'محفظة',
                                    'stripe' => 'Stripe',
                                ])
                                ->default('bank_transfer')
                                ->required(),

                            Select::make('status')
                                ->label('الحالة')
                                ->options(PayoutStatus::options())
                                ->default(PayoutStatus::Pending->value)
                                ->required(),

                            TextInput::make('transaction_id')
                                ->label('رقم المعاملة')
                                ->maxLength(255),

                            TextInput::make('reference_number')
                                ->label('الرقم المرجعي')
                                ->maxLength(255),

                            DateTimePicker::make('paid_at')
                                ->label('تاريخ الدفع')
                                ->timezone('Asia/Riyadh'),

                            Textarea::make('notes')
                                ->label('ملاحظات')
                                ->rows(2)
                                ->columnSpan(2),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('رقم التحويل')
                    ->formatStateUsing(fn ($state) => '#' . str_pad($state, 5, '0', STR_PAD_LEFT))
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('المبلغ')
                    ->money('SAR')
                    ->sortable(),

                TextColumn::make('method')
                    ->label('الطريقة')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'bank_transfer' => 'تحويل بنكي',
                        'wallet' => 'محفظة',
                        'stripe' => 'Stripe',
                        default => $state,
                    }),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn ($state) => $state?->color() ?? 'gray')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? $state),

                TextColumn::make('paid_at')
                    ->label('تاريخ الدفع')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(PayoutStatus::options()),

                SelectFilter::make('method')
                    ->label('طريقة الدفع')
                    ->options([
                        'bank_transfer' => 'تحويل بنكي',
                        'wallet' => 'محفظة',
                        'stripe' => 'Stripe',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),

                Actions\Action::make('complete')
                    ->label('إكمال')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Payout $record) {
                        $record->update([
                            'status' => PayoutStatus::Completed->value,
                            'paid_at' => now(),
                        ]);
                    })
                    ->visible(fn (Payout $record) => $record->status !== PayoutStatus::Completed),
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
            'index' => Pages\ListPayouts::route('/'),
            'create' => Pages\CreatePayout::route('/create'),
            'edit' => Pages\EditPayout::route('/{record}/edit'),
        ];
    }
}
