<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailCampaignResource\Pages;
use App\Models\EmailCampaign;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Artisan;

class EmailCampaignResource extends Resource
{
    protected static ?string $model = EmailCampaign::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'حملات البريد';
    protected static \UnitEnum|string|null $navigationGroup = 'التسويق';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('اسم الحملة')
                ->required()
                ->maxLength(255),
            TextInput::make('subject')
                ->label('عنوان البريد')
                ->required()
                ->maxLength(255),
            Textarea::make('body')
                ->label('محتوى الرسالة')
                ->required()
                ->rows(10)
                ->helperText('يمكنك كتابة نص عادي. سيتم تنسيقه تلقائياً.'),
            Select::make('target_audience')
                ->label('الجمهور المستهدف')
                ->options([
                    'all' => 'جميع المستخدمين',
                    'customers' => 'العملاء فقط',
                    'partners' => 'شركاء الطباعة',
                    'resellers' => 'الموزعون',
                    'designers' => 'المصممون',
                    'affiliates' => 'المسوّقون',
                ])
                ->default('all')
                ->required(),
            DateTimePicker::make('scheduled_at')
                ->label('موعد الإرسال')
                ->helperText('اتركه فارغاً للإرسال يدوياً'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم الحملة')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('العنوان')
                    ->limit(40),
                TextColumn::make('target_audience')
                    ->label('الجمهور')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'all' => 'الجميع',
                        'customers' => 'العملاء',
                        'partners' => 'الشركاء',
                        'resellers' => 'الموزعون',
                        'designers' => 'المصممون',
                        'affiliates' => 'المسوّقون',
                        default => $state,
                    }),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'warning',
                        'sending' => 'info',
                        'sent' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'مسودة',
                        'scheduled' => 'مجدول',
                        'sending' => 'جارٍ الإرسال',
                        'sent' => 'تم الإرسال',
                        default => $state,
                    }),
                TextColumn::make('sent_count')
                    ->label('المُرسل')
                    ->suffix(' / ')
                    ->state(fn (EmailCampaign $record) => "{$record->sent_count}/{$record->recipients_count}"),
                TextColumn::make('sent_at')
                    ->label('تاريخ الإرسال')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('لم يُرسل بعد'),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'draft' => 'مسودة',
                        'scheduled' => 'مجدول',
                        'sending' => 'جارٍ الإرسال',
                        'sent' => 'تم الإرسال',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('send')
                    ->label('إرسال الآن')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('تأكيد الإرسال')
                    ->modalDescription('هل أنت متأكد من إرسال هذه الحملة؟ لا يمكن التراجع بعد الإرسال.')
                    ->visible(fn (EmailCampaign $record) => $record->status === 'draft' || $record->status === 'scheduled')
                    ->action(function (EmailCampaign $record) {
                        Artisan::queue('mail:send-campaign', ['campaign_id' => $record->id]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailCampaigns::route('/'),
            'create' => Pages\CreateEmailCampaign::route('/create'),
            'edit' => Pages\EditEmailCampaign::route('/{record}/edit'),
        ];
    }
}
