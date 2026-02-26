<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CardResource\Pages;
use App\Models\Card;
use App\Models\Template;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'البطاقات';
    protected static \UnitEnum|string|null $navigationGroup = 'إدارة المحتوى';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('المعلومات الأساسية')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('user_id')
                                ->label('المالك')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->required(),

                            Select::make('template_id')
                                ->label('القالب')
                                ->options(Template::where('is_active', true)->pluck('name_ar', 'id'))
                                ->searchable()
                                ->required(),

                            TextInput::make('slug')
                                ->label('الرابط المخصص')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            TextInput::make('title')
                                ->label('عنوان البطاقة')
                                ->required()
                                ->maxLength(255),

                            Toggle::make('is_active')
                                ->label('نشطة')
                                ->default(true),

                            Toggle::make('is_public')
                                ->label('عامة')
                                ->default(true),
                        ]),
                    ]),

                Section::make('المعلومات الشخصية')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('full_name')
                                ->label('الاسم الكامل')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('job_title')
                                ->label('المسمى الوظيفي')
                                ->maxLength(255),

                            TextInput::make('company')
                                ->label('الشركة')
                                ->maxLength(255),

                            Textarea::make('bio')
                                ->label('نبذة تعريفية')
                                ->rows(3)
                                ->maxLength(500)
                                ->columnSpan(2),
                        ]),
                    ]),

                Section::make('معلومات الاتصال')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('email')
                                ->label('البريد الإلكتروني')
                                ->email()
                                ->maxLength(255),

                            TextInput::make('phone')
                                ->label('رقم الجوال')
                                ->tel()
                                ->maxLength(255),

                            TextInput::make('whatsapp')
                                ->label('واتساب')
                                ->tel()
                                ->maxLength(255),

                            TextInput::make('website')
                                ->label('الموقع الإلكتروني')
                                ->url()
                                ->maxLength(255),

                            Textarea::make('address')
                                ->label('العنوان')
                                ->rows(2)
                                ->maxLength(500)
                                ->columnSpan(2),
                        ]),
                    ]),

                Section::make('الصور')
                    ->schema([
                        Grid::make(3)->schema([
                            FileUpload::make('profile_image')
                                ->label('صورة الملف الشخصي')
                                ->image()
                                ->directory('cards/profiles'),

                            FileUpload::make('cover_image')
                                ->label('صورة الغلاف')
                                ->image()
                                ->directory('cards/covers'),

                            FileUpload::make('logo')
                                ->label('الشعار')
                                ->image()
                                ->directory('cards/logos'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_image')
                    ->label('الصورة')
                    ->circular(),

                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->label('الرابط')
                    ->searchable()
                    ->copyable()
                    ->limit(30),

                TextColumn::make('user.name')
                    ->label('المالك')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_public')
                    ->label('عامة')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('views_count')
                    ->label('المشاهدات')
                    ->sortable()
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('نشطة')
                    ->placeholder('الكل')
                    ->trueLabel('نشطة فقط')
                    ->falseLabel('غير نشطة فقط'),

                TernaryFilter::make('is_public')
                    ->label('عامة')
                    ->placeholder('الكل')
                    ->trueLabel('عامة فقط')
                    ->falseLabel('خاصة فقط'),

                SelectFilter::make('template_id')
                    ->label('القالب')
                    ->relationship('template', 'name_ar'),

                SelectFilter::make('user_id')
                    ->label('المالك')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),

                Actions\Action::make('visit')
                    ->label('زيارة')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Card $record): string => url($record->slug))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),

                    Actions\BulkAction::make('activate')
                        ->label('تفعيل')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),

                    Actions\BulkAction::make('deactivate')
                        ->label('تعطيل')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
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
            'index' => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'edit' => Pages\EditCard::route('/{record}/edit'),
        ];
    }
}
