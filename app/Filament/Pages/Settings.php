<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Cache;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'الإعدادات';
    protected static \UnitEnum|string|null $navigationGroup = 'الإعدادات';
    protected static ?string $title = 'إعدادات الموقع';
    protected static ?int $navigationSort = 99;
    protected string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettings());
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Tabs::make('الإعدادات')
                    ->tabs([
                        Tabs\Tab::make('عام')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('site_name')
                                        ->label('اسم الموقع')
                                        ->default('Maroof ID')
                                        ->required(),

                                    TextInput::make('site_email')
                                        ->label('البريد الإلكتروني')
                                        ->email()
                                        ->default('info@maroof.sa'),

                                    TextInput::make('site_phone')
                                        ->label('رقم الجوال')
                                        ->tel()
                                        ->default('+966'),

                                    TextInput::make('site_url')
                                        ->label('رابط الموقع')
                                        ->url()
                                        ->default('https://maroof.sa'),

                                    Textarea::make('site_description')
                                        ->label('وصف الموقع')
                                        ->rows(3)
                                        ->columnSpan(2),
                                ]),
                            ]),

                        Tabs\Tab::make('الأسعار')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('card_price')
                                        ->label('سعر البطاقة الأساسي')
                                        ->numeric()
                                        ->prefix('ر.س')
                                        ->default(99),

                                    TextInput::make('premium_card_price')
                                        ->label('سعر البطاقة المميزة')
                                        ->numeric()
                                        ->prefix('ر.س')
                                        ->default(199),

                                    TextInput::make('tax_rate')
                                        ->label('نسبة الضريبة (%)')
                                        ->numeric()
                                        ->suffix('%')
                                        ->default(15),

                                    TextInput::make('shipping_fee')
                                        ->label('رسوم الشحن الافتراضية')
                                        ->numeric()
                                        ->prefix('ر.س')
                                        ->default(25),

                                    Select::make('currency')
                                        ->label('العملة')
                                        ->options([
                                            'SAR' => 'ريال سعودي (SAR)',
                                            'AED' => 'درهم إماراتي (AED)',
                                            'KWD' => 'دينار كويتي (KWD)',
                                            'USD' => 'دولار أمريكي (USD)',
                                        ])
                                        ->default('SAR'),
                                ]),
                            ]),

                        Tabs\Tab::make('الشحن')
                            ->icon('heroicon-o-truck')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('shipping_enabled')
                                        ->label('تفعيل الشحن')
                                        ->default(true),

                                    Toggle::make('free_shipping_enabled')
                                        ->label('شحن مجاني فوق مبلغ معين')
                                        ->default(false),

                                    TextInput::make('free_shipping_threshold')
                                        ->label('الحد الأدنى للشحن المجاني')
                                        ->numeric()
                                        ->prefix('ر.س')
                                        ->default(500),

                                    TextInput::make('estimated_delivery_days')
                                        ->label('مدة التوصيل المتوقعة (أيام)')
                                        ->numeric()
                                        ->default(5),
                                ]),
                            ]),

                        Tabs\Tab::make('الإشعارات')
                            ->icon('heroicon-o-bell')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('email_notifications')
                                        ->label('إشعارات البريد الإلكتروني')
                                        ->default(true),

                                    Toggle::make('sms_notifications')
                                        ->label('إشعارات SMS')
                                        ->default(false),

                                    Toggle::make('order_notification')
                                        ->label('إشعار عند طلب جديد')
                                        ->default(true),

                                    Toggle::make('payment_notification')
                                        ->label('إشعار عند الدفع')
                                        ->default(true),
                                ]),
                            ]),

                        Tabs\Tab::make('الصيانة')
                            ->icon('heroicon-o-wrench')
                            ->schema([
                                Toggle::make('maintenance_mode')
                                    ->label('وضع الصيانة')
                                    ->helperText('عند التفعيل، سيظهر للزوار صفحة صيانة')
                                    ->default(false),

                                Textarea::make('maintenance_message')
                                    ->label('رسالة الصيانة')
                                    ->rows(3)
                                    ->default('الموقع تحت الصيانة، سنعود قريباً'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('حفظ الإعدادات')
                ->action('save')
                ->color('primary')
                ->icon('heroicon-o-check'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Cache::forever("settings.{$key}", $value);
        }

        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();
    }

    protected function getSettings(): array
    {
        $defaults = [
            'site_name' => 'Maroof ID',
            'site_email' => 'info@maroof.sa',
            'site_phone' => '+966',
            'site_url' => 'https://maroof.sa',
            'site_description' => 'منصة البطاقات الرقمية الذكية',
            'card_price' => 99,
            'premium_card_price' => 199,
            'tax_rate' => 15,
            'shipping_fee' => 25,
            'currency' => 'SAR',
            'shipping_enabled' => true,
            'free_shipping_enabled' => false,
            'free_shipping_threshold' => 500,
            'estimated_delivery_days' => 5,
            'email_notifications' => true,
            'sms_notifications' => false,
            'order_notification' => true,
            'payment_notification' => true,
            'maintenance_mode' => false,
            'maintenance_message' => 'الموقع تحت الصيانة، سنعود قريباً',
        ];

        $settings = [];
        foreach ($defaults as $key => $default) {
            $settings[$key] = Cache::get("settings.{$key}", $default);
        }

        return $settings;
    }
}
