<?php

namespace App\Filament\Resources;

use App\Enums\GeneralStatus;
use App\Filament\Resources\DesignerResource\Pages;
use App\Models\Designer;
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

class DesignerResource extends Resource
{
    protected static ?string $model = Designer::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'المصممون';
    protected static \UnitEnum|string|null $navigationGroup = 'الشركاء';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات المصمم')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('user_id')
                                ->label('المستخدم')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->required(),

                            TextInput::make('portfolio_url')
                                ->label('رابط المعرض')
                                ->url()
                                ->maxLength(500),

                            Textarea::make('bio')
                                ->label('السيرة الذاتية')
                                ->rows(3)
                                ->maxLength(1000)
                                ->columnSpan(2),

                            TextInput::make('rating')
                                ->label('التقييم')
                                ->numeric()
                                ->disabled()
                                ->default(0)
                                ->suffix('/ 5'),

                            TextInput::make('templates_count')
                                ->label('عدد القوالب')
                                ->numeric()
                                ->disabled()
                                ->default(0),

                            TextInput::make('earnings')
                                ->label('الأرباح الإجمالية')
                                ->numeric()
                                ->disabled()
                                ->prefix('ر.س')
                                ->default(0),

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
                    ->label('المصمم')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('templates_count')
                    ->label('القوالب')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                TextColumn::make('rating')
                    ->label('التقييم')
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . ' / 5')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

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
            ->defaultSort('templates_count', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDesigners::route('/'),
            'create' => Pages\CreateDesigner::route('/create'),
            'edit' => Pages\EditDesigner::route('/{record}/edit'),
        ];
    }
}
