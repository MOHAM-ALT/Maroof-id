<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateResource\Pages;
use App\Models\Template;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'القوالب';
    protected static \UnitEnum|string|null $navigationGroup = 'إدارة المحتوى';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('معلومات القالب')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name_ar')
                        ->label('اسم القالب (عربي)')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('name_en')
                        ->label('اسم القالب (إنجليزي)')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('slug')
                        ->label('الرابط الودود')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Select::make('template_category_id')
                        ->label('الفئة')
                        ->relationship('category', 'name_ar')
                        ->required(),

                    Textarea::make('description_ar')
                        ->label('الوصف (عربي)')
                        ->rows(3)
                        ->columnSpan(2),

                    Textarea::make('description_en')
                        ->label('الوصف (إنجليزي)')
                        ->rows(3)
                        ->columnSpan(2),
                ]),
            ]),

            Section::make('التفاصيل')->schema([
                Grid::make(2)->schema([
                    TextInput::make('price')
                        ->label('السعر')
                        ->numeric()
                        ->prefix('ر.س')
                        ->default(0)
                        ->required(),

                    FileUpload::make('preview_image')
                        ->label('صورة المعاينة')
                        ->image()
                        ->directory('templates/previews'),

                    Toggle::make('is_active')
                        ->label('نشط')
                        ->default(true),

                    Toggle::make('is_featured')
                        ->label('مميز')
                        ->default(false),

                    Toggle::make('is_premium')
                        ->label('مدفوع')
                        ->default(false),

                    TextInput::make('sort_order')
                        ->label('الترتيب')
                        ->numeric()
                        ->default(0),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_ar')
                    ->label('اسم القالب')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name_ar')
                    ->label('الفئة')
                    ->badge(),

                TextColumn::make('price')
                    ->label('السعر')
                    ->money('SAR')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('usage_count')
                    ->label('الاستخدامات')
                    ->sortable()
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('template_category_id')
                    ->label('الفئة')
                    ->relationship('category', 'name_ar'),
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
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
