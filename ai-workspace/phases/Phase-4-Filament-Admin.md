# 🎨 Phase 4: Filament Admin Dashboard - الخطة العملية

**التاريخ:** 15 فبراير 2026  
**الوقت المتوقع:** 10 ساعات  
**الحالة:** ⏳ 20% مكتمل  
**الأولوية:** 🔴 عالية

---

## 📊 نظرة عامة

### الهدف:
بناء لوحة تحكم إدارية كاملة باستخدام Filament Panel لإدارة كامل المنصة.

### ما تم:
- ✅ UserResource (أساسي)
- ✅ TemplateResource (أساسي)
- ⏳ باقي Resources تحتاج إنشاء

### ما ينقص:
- ❌ Dashboard Widgets (4 widgets)
- ❌ 8 Resources (Card, Order, Partner, Reseller, Designer, Affiliate, Coupon, Payout)
- ❌ Custom Pages (Settings, Reports)
- ❌ Relation Managers
- ❌ Global Search
- ❌ Navigation Groups

---

## 🎯 خطة العمل - 4 مراحل

### المرحلة 1: Dashboard & Widgets (2 ساعات) 🔴
### المرحلة 2: Core Resources (4 ساعات) 🔴
### المرحلة 3: Advanced Resources (3 ساعات) 🟡
### المرحلة 4: Settings & Polish (1 ساعة) 🟢

---

# 📋 المرحلة 1: Dashboard & Widgets

**⏰ الوقت:** 2 ساعات  
**الأولوية:** 🔴 عالية جداً

---

## 1.1 - StatsOverviewWidget

**الملف:** `app/Filament/Widgets/StatsOverviewWidget.php`  
**⏰ الوقت:** 30 دقيقة

### الأمر:
```bash
php artisan make:filament-widget StatsOverviewWidget --stats
```

### الكود:

```php
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Card;
use App\Models\Order;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;
    
    protected function getStats(): array
    {
        // حسابات بسيطة وواضحة
        $totalUsers = User::count();
        $usersThisMonth = User::whereMonth('created_at', now()->month)->count();
        $usersGrowth = $totalUsers > 0 ? round(($usersThisMonth / $totalUsers) * 100, 1) : 0;
        
        $totalCards = Card::count();
        $activeCards = Card::where('is_active', true)->count();
        
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $revenueThisMonth = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('total');
        
        return [
            Stat::make('إجمالي المستخدمين', number_format($totalUsers))
                ->description("زيادة {$usersGrowth}% هذا الشهر")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->color('success'),
            
            Stat::make('البطاقات', number_format($totalCards))
                ->description("{$activeCards} نشطة")
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('info'),
            
            Stat::make('الطلبات', number_format($totalOrders))
                ->description("{$pendingOrders} قيد الانتظار")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),
            
            Stat::make('الإيرادات', number_format($totalRevenue, 2) . ' ر.س')
                ->description(number_format($revenueThisMonth, 2) . ' ر.س هذا الشهر')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
```

---

## 1.2 - RevenueChartWidget

**الملف:** `app/Filament/Widgets/RevenueChartWidget.php`  
**⏰ الوقت:** 30 دقيقة

### الأمر:
```bash
php artisan make:filament-widget RevenueChartWidget --chart
```

### الكود:

```php
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'الإيرادات - آخر 30 يوم';
    protected static ?int $sort = 1;
    
    protected function getData(): array
    {
        // استعلام بسيط وفعال
        $data = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'datasets' => [
                [
                    'label' => 'الإيرادات (ر.س)',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'labels' => $data->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d/m');
            })->toArray(),
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
}
```

---

## 1.3 - OrdersChartWidget

**الملف:** `app/Filament/Widgets/OrdersChartWidget.php`  
**⏰ الوقت:** 30 دقيقة

### الأمر:
```bash
php artisan make:filament-widget OrdersChartWidget --chart
```

### الكود:

```php
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;

class OrdersChartWidget extends ChartWidget
{
    protected static ?string $heading = 'الطلبات حسب الحالة';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';
    
    protected function getData(): array
    {
        // عد الطلبات حسب الحالة
        $pending = Order::where('status', 'pending')->count();
        $processing = Order::where('status', 'processing')->count();
        $completed = Order::where('status', 'completed')->count();
        $cancelled = Order::where('status', 'cancelled')->count();
        
        return [
            'datasets' => [
                [
                    'label' => 'الطلبات',
                    'data' => [$pending, $processing, $completed, $cancelled],
                    'backgroundColor' => [
                        'rgb(251, 191, 36)',  // أصفر - معلق
                        'rgb(59, 130, 246)',  // أزرق - قيد المعالجة
                        'rgb(34, 197, 94)',   // أخضر - مكتمل
                        'rgb(239, 68, 68)',   // أحمر - ملغي
                    ],
                ],
            ],
            'labels' => ['معلق', 'قيد المعالجة', 'مكتمل', 'ملغي'],
        ];
    }
    
    protected function getType(): string
    {
        return 'doughnut';
    }
}
```

---

## 1.4 - LatestOrdersWidget

**الملف:** `app/Filament/Widgets/LatestOrdersWidget.php`  
**⏰ الوقت:** 30 دقيقة

### الأمر:
```bash
php artisan make:filament-widget LatestOrdersWidget --table
```

### الكود:

```php
<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Order;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('رقم الطلب')
                    ->formatStateUsing(fn ($state) => '#' . str_pad($state, 5, '0', STR_PAD_LEFT)),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل'),
                
                Tables\Columns\TextColumn::make('total')
                    ->label('المبلغ')
                    ->money('SAR'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
```

---

## ✅ ملخص المرحلة 1:

### الملفات المُنشأة:
```
app/Filament/Widgets/
├── StatsOverviewWidget.php ✅
├── RevenueChartWidget.php ✅
├── OrdersChartWidget.php ✅
└── LatestOrdersWidget.php ✅
```

### الأوامر المطلوبة:
```bash
# نسخ/لصق جميعاً:
php artisan make:filament-widget StatsOverviewWidget --stats
php artisan make:filament-widget RevenueChartWidget --chart
php artisan make:filament-widget OrdersChartWidget --chart
php artisan make:filament-widget LatestOrdersWidget --table
```

**⏰ الوقت الفعلي:** 2 ساعات

---

# 📋 المرحلة 2: Core Resources

**⏰ الوقت:** 4 ساعات  
**الأولوية:** 🔴 عالية

---

## 2.1 - CardResource (محسّن)

**الملف:** `app/Filament/Resources/CardResource.php`  
**⏰ الوقت:** 1 ساعة

### الأمر:
```bash
# إذا غير موجود:
php artisan make:filament-resource Card --generate

# إذا موجود: فقط عدّل الملف
```

### الكود الكامل:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CardResource\Pages;
use App\Models\Card;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'البطاقات';
    protected static ?string $modelLabel = 'بطاقة';
    protected static ?string $pluralModelLabel = 'البطاقات';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'إدارة المحتوى';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('المالك')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('email')->email()->required(),
                                Forms\Components\TextInput::make('password')->password()->required(),
                            ]),
                        
                        Forms\Components\Select::make('template_id')
                            ->label('القالب')
                            ->options(Template::where('is_active', true)->pluck('name_ar', 'id'))
                            ->searchable()
                            ->required()
                            ->live(),
                        
                        Forms\Components\TextInput::make('slug')
                            ->label('الرابط المخصص')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->prefix(url('/'))
                            ->maxLength(255)
                            ->helperText('سيكون الرابط: ' . url('/') . '/رابطك'),
                        
                        Forms\Components\TextInput::make('title')
                            ->label('عنوان البطاقة')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشطة')
                            ->default(true)
                            ->inline(false),
                        
                        Forms\Components\Toggle::make('is_public')
                            ->label('عامة')
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),
                
                Forms\Components\Section::make('المعلومات الشخصية')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label('الاسم الكامل')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('job_title')
                            ->label('المسمى الوظيفي')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('company')
                            ->label('الشركة')
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('bio')
                            ->label('نبذة تعريفية')
                            ->rows(3)
                            ->maxLength(500),
                    ])->columns(2),
                
                Forms\Components\Section::make('معلومات الاتصال')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الجوال')
                            ->tel()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('واتساب')
                            ->tel()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('website')
                            ->label('الموقع الإلكتروني')
                            ->url()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('address')
                            ->label('العنوان')
                            ->rows(2)
                            ->maxLength(500),
                    ])->columns(2),
                
                Forms\Components\Section::make('الصور')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_image')
                            ->label('صورة الملف الشخصي')
                            ->image()
                            ->directory('cards/profiles')
                            ->maxSize(2048)
                            ->imageEditor(),
                        
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('صورة الغلاف')
                            ->image()
                            ->directory('cards/covers')
                            ->maxSize(4096)
                            ->imageEditor(),
                        
                        Forms\Components\FileUpload::make('logo')
                            ->label('الشعار')
                            ->image()
                            ->directory('cards/logos')
                            ->maxSize(1024)
                            ->imageEditor(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('slug')
                    ->label('الرابط')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('تم نسخ الرابط!')
                    ->formatStateUsing(fn ($state) => url($state))
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('المالك')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_public')
                    ->label('عامة')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('views_count')
                    ->label('المشاهدات')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشطة')
                    ->placeholder('الكل')
                    ->trueLabel('نشطة فقط')
                    ->falseLabel('غير نشطة فقط'),
                
                Tables\Filters\TernaryFilter::make('is_public')
                    ->label('عامة')
                    ->placeholder('الكل')
                    ->trueLabel('عامة فقط')
                    ->falseLabel('خاصة فقط'),
                
                Tables\Filters\SelectFilter::make('template_id')
                    ->label('القالب')
                    ->relationship('template', 'name_ar'),
                
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('المالك')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                Tables\Actions\Action::make('visit')
                    ->label('زيارة')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Card $record): string => url($record->slug))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('activate')
                        ->label('تفعيل')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    
                    Tables\Actions\BulkAction::make('deactivate')
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
            'view' => Pages\ViewCard::route('/{record}'),
            'edit' => Pages\EditCard::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
```

---

## 2.2 - OrderResource (محسّن)

**الملف:** `app/Filament/Resources/OrderResource.php`  
**⏰ الوقت:** 1 ساعة

### الكود الكامل:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'الطلبات';
    protected static ?string $modelLabel = 'طلب';
    protected static ?string $pluralModelLabel = 'الطلبات';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'إدارة المبيعات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الطلب')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('رقم الطلب')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(fn () => 'MRF-' . strtoupper(uniqid())),
                        
                        Forms\Components\Select::make('user_id')
                            ->label('العميل')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        
                        Forms\Components\Select::make('card_id')
                            ->label('البطاقة')
                            ->relationship('card', 'title')
                            ->searchable()
                            ->nullable(),
                        
                        Forms\Components\Select::make('type')
                            ->label('نوع الطلب')
                            ->options([
                                'physical_card' => 'بطاقة فيزيائية',
                                'digital_only' => 'رقمي فقط',
                                'custom_design' => 'تصميم مخصص',
                                'bulk' => 'طلب جملة',
                            ])
                            ->required()
                            ->default('physical_card'),
                        
                        Forms\Components\TextInput::make('quantity')
                            ->label('الكمية')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('التسعير')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('المجموع الفرعي')
                            ->numeric()
                            ->prefix('ر.س')
                            ->required(),
                        
                        Forms\Components\TextInput::make('tax')
                            ->label('الضريبة (15%)')
                            ->numeric()
                            ->prefix('ر.س')
                            ->default(0),
                        
                        Forms\Components\TextInput::make('shipping_fee')
                            ->label('رسوم الشحن')
                            ->numeric()
                            ->prefix('ر.س')
                            ->default(0),
                        
                        Forms\Components\TextInput::make('discount')
                            ->label('الخصم')
                            ->numeric()
                            ->prefix('ر.س')
                            ->default(0),
                        
                        Forms\Components\TextInput::make('total')
                            ->label('الإجمالي')
                            ->numeric()
                            ->prefix('ر.س')
                            ->required(),
                    ])->columns(3),
                
                Forms\Components\Section::make('الحالة')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('حالة الطلب')
                            ->options([
                                'pending' => 'معلق',
                                'confirmed' => 'مؤكد',
                                'processing' => 'قيد المعالجة',
                                'completed' => 'مكتمل',
                                'cancelled' => 'ملغي',
                            ])
                            ->default('pending')
                            ->required()
                            ->live(),
                        
                        Forms\Components\Select::make('payment_status')
                            ->label('حالة الدفع')
                            ->options([
                                'pending' => 'معلق',
                                'paid' => 'مدفوع',
                                'failed' => 'فشل',
                                'refunded' => 'مسترجع',
                            ])
                            ->default('pending')
                            ->required(),
                        
                        Forms\Components\Select::make('payment_method')
                            ->label('طريقة الدفع')
                            ->options([
                                'tap' => 'Tap',
                                'stc_pay' => 'STC Pay',
                                'mada' => 'مدى',
                                'visa' => 'Visa',
                                'mastercard' => 'Mastercard',
                            ])
                            ->nullable(),
                        
                        Forms\Components\Select::make('shipping_status')
                            ->label('حالة الشحن')
                            ->options([
                                'pending' => 'معلق',
                                'processing' => 'قيد التجهيز',
                                'shipped' => 'تم الشحن',
                                'delivered' => 'تم التوصيل',
                                'cancelled' => 'ملغي',
                            ])
                            ->default('pending'),
                    ])->columns(2),
                
                Forms\Components\Section::make('عنوان الشحن')
                    ->schema([
                        Forms\Components\Textarea::make('shipping_address')
                            ->label('العنوان')
                            ->rows(2)
                            ->maxLength(500),
                        
                        Forms\Components\TextInput::make('shipping_city')
                            ->label('المدينة')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('shipping_postal_code')
                            ->label('الرمز البريدي')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('shipping_phone')
                            ->label('رقم الجوال')
                            ->tel()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('رقم التتبع')
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('ملاحظات')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات العميل')
                            ->rows(2),
                        
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('ملاحظات الإدارة')
                            ->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->copyable()
                    ->copyMessage('تم النسخ!'),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('card.title')
                    ->label('البطاقة')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->colors([
                        'primary' => 'physical_card',
                        'info' => 'digital_only',
                        'warning' => 'custom_design',
                        'success' => 'bulk',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'physical_card' => 'فيزيائية',
                        'digital_only' => 'رقمي',
                        'custom_design' => 'مخصص',
                        'bulk' => 'جملة',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('total')
                    ->label('الإجمالي')
                    ->money('SAR')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('حالة الطلب')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'معلق',
                        'confirmed' => 'مؤكد',
                        'processing' => 'قيد المعالجة',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                        default => $state,
                    }),
                
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('الدفع')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'info' => 'refunded',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'معلق',
                        'paid' => 'مدفوع',
                        'failed' => 'فشل',
                        'refunded' => 'مسترجع',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الطلب')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('حالة الطلب')
                    ->options([
                        'pending' => 'معلق',
                        'confirmed' => 'مؤكد',
                        'processing' => 'قيد المعالجة',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                    ]),
                
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('حالة الدفع')
                    ->options([
                        'pending' => 'معلق',
                        'paid' => 'مدفوع',
                        'failed' => 'فشل',
                        'refunded' => 'مسترجع',
                    ]),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('change_status')
                    ->label('تغيير الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('status')
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
                        // TODO: Send notification email
                    })
                    ->color('warning'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
```

---

**بسبب طول الملف، سأكمل في ملف منفصل...**

