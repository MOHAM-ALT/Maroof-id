<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Card;
use App\Models\Order;
use App\Models\Template;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('بدء حقن البيانات الوهمية...');

        // 1. إنشاء مستخدمين وهميين
        $this->command->info('إنشاء 50 مستخدم...');
        $users = [];
        $arabicNames = [
            'أحمد محمد', 'فاطمة علي', 'خالد العمري', 'نورة السعيد', 'محمد الحربي',
            'سارة الشمري', 'عبدالله الدوسري', 'مريم الغامدي', 'فهد القحطاني', 'هند الزهراني',
            'سلطان المطيري', 'ريم العتيبي', 'ناصر الشهري', 'لمى الحربي', 'تركي السبيعي',
            'دانة المالكي', 'بندر الجهني', 'أسماء البلوي', 'ماجد الرشيدي', 'غادة الحارثي',
            'سعود العنزي', 'منال الثبيتي', 'راشد السلمي', 'وفاء الأحمدي', 'عمر الزيلعي',
            'هيفاء المحمدي', 'يوسف الخالدي', 'شيماء الرحيلي', 'إبراهيم النفيعي', 'أمل الصاعدي',
            'مشعل العوفي', 'عهود الكنعاني', 'فيصل المرواني', 'ندى البارقي', 'حمد الشلوي',
            'رنا التميمي', 'صالح الشمراني', 'بسمة الرويلي', 'طلال العمري', 'جنان الهذلي',
            'عادل الفهمي', 'حصة الدعجاني', 'زياد القرشي', 'ملاك الطويرقي', 'وليد البقمي',
            'ديمة المعبدي', 'حسن الزبيدي', 'لطيفة الفيفي', 'أنس الجابري', 'رقية العسيري',
        ];

        for ($i = 0; $i < 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(1, 365));
            $user = User::create([
                'name' => $arabicNames[$i],
                'email' => 'user' . ($i + 1) . '@demo.maroof.sa',
                'password' => bcrypt('password'),
                'email_verified_at' => rand(0, 1) ? $createdAt : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            $users[] = $user;
        }

        // 2. إنشاء فئات القوالب (إذا لم تكن موجودة)
        $this->command->info('التأكد من فئات القوالب...');
        $categories = DB::table('template_categories')->get();
        if ($categories->isEmpty()) {
            $catData = [
                ['name_ar' => 'أعمال', 'name_en' => 'Business', 'slug' => 'business', 'sort_order' => 1],
                ['name_ar' => 'شخصي', 'name_en' => 'Personal', 'slug' => 'personal', 'sort_order' => 2],
                ['name_ar' => 'احترافي', 'name_en' => 'Professional', 'slug' => 'professional', 'sort_order' => 3],
                ['name_ar' => 'إبداعي', 'name_en' => 'Creative', 'slug' => 'creative', 'sort_order' => 4],
            ];
            foreach ($catData as $cat) {
                DB::table('template_categories')->insert(array_merge($cat, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
            $categories = DB::table('template_categories')->get();
        }

        // 3. إنشاء قوالب
        $this->command->info('إنشاء 12 قالب...');
        $templateNames = [
            ['ar' => 'أنيق', 'en' => 'Elegant'],
            ['ar' => 'عصري', 'en' => 'Modern'],
            ['ar' => 'كلاسيكي', 'en' => 'Classic'],
            ['ar' => 'بسيط', 'en' => 'Minimal'],
            ['ar' => 'جريء', 'en' => 'Bold'],
            ['ar' => 'أنيق ذهبي', 'en' => 'Gold Elegant'],
            ['ar' => 'تقني', 'en' => 'Tech'],
            ['ar' => 'طبيعي', 'en' => 'Nature'],
            ['ar' => 'ملكي', 'en' => 'Royal'],
            ['ar' => 'نابض', 'en' => 'Vibrant'],
            ['ar' => 'داكن', 'en' => 'Dark'],
            ['ar' => 'فاتح', 'en' => 'Light'],
        ];

        $templates = [];
        foreach ($templateNames as $idx => $name) {
            $catId = $categories->random()->id;
            $template = Template::create([
                'template_category_id' => $catId,
                'name_ar' => $name['ar'],
                'name_en' => $name['en'],
                'slug' => Str::slug($name['en']),
                'description_ar' => 'قالب ' . $name['ar'] . ' - تصميم احترافي لبطاقتك الرقمية',
                'description_en' => $name['en'] . ' template - Professional design for your digital card',
                'price' => [0, 0, 29, 49, 99, 149][rand(0, 5)],
                'is_active' => true,
                'is_featured' => rand(0, 1),
                'is_premium' => rand(0, 1),
                'usage_count' => rand(5, 500),
                'sort_order' => $idx,
                'created_at' => now()->subDays(rand(10, 100)),
            ]);
            $templates[] = $template;
        }

        // 4. إنشاء بطاقات
        $this->command->info('إنشاء 80 بطاقة...');
        $jobTitles = ['مدير تنفيذي', 'مهندس برمجيات', 'مصمم جرافيك', 'مدير تسويق', 'محاسب', 'طبيب', 'محامي', 'مستشار', 'مطور ويب', 'مدير مشاريع'];
        $companies = ['شركة التقنية', 'مجموعة الأعمال', 'مؤسسة الابتكار', 'شركة المستقبل', 'مؤسسة التميز', 'شركة الحلول', 'مجموعة الرؤية', 'شركة الإبداع'];
        $cities = ['الرياض', 'جدة', 'الدمام', 'مكة', 'المدينة', 'الخبر', 'تبوك', 'أبها', 'الطائف', 'بريدة'];

        $cards = [];
        foreach ($users as $idx => $user) {
            $numCards = rand(1, 3);
            for ($c = 0; $c < $numCards && count($cards) < 80; $c++) {
                $createdAt = Carbon::parse($user->created_at)->addDays(rand(1, 30));
                $card = Card::create([
                    'user_id' => $user->id,
                    'template_id' => $templates[array_rand($templates)]->id,
                    'slug' => 'card-' . Str::random(8),
                    'title' => 'بطاقة ' . $user->name,
                    'full_name' => $user->name,
                    'job_title' => $jobTitles[array_rand($jobTitles)],
                    'company' => $companies[array_rand($companies)],
                    'email' => $user->email,
                    'phone' => '05' . rand(10000000, 99999999),
                    'bio' => 'متخصص في مجال العمل مع خبرة تزيد عن ' . rand(3, 20) . ' سنوات',
                    'is_active' => rand(0, 10) > 1,
                    'is_public' => rand(0, 10) > 2,
                    'views_count' => rand(0, 5000),
                    'address' => $cities[array_rand($cities)],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                $cards[] = $card;
            }
        }

        // 5. إنشاء مشاهدات البطاقات (للتحليلات)
        $this->command->info('إنشاء 500 مشاهدة...');
        $countries = ['SA' => 'السعودية', 'AE' => 'الإمارات', 'KW' => 'الكويت', 'BH' => 'البحرين', 'QA' => 'قطر', 'OM' => 'عمان', 'EG' => 'مصر', 'JO' => 'الأردن', 'US' => 'أمريكا', 'GB' => 'بريطانيا', 'DE' => 'ألمانيا', 'TR' => 'تركيا', 'IN' => 'الهند', 'PK' => 'باكستان'];
        $countryWeights = ['السعودية' => 40, 'الإمارات' => 15, 'الكويت' => 8, 'البحرين' => 5, 'قطر' => 5, 'عمان' => 4, 'مصر' => 8, 'الأردن' => 3, 'أمريكا' => 3, 'بريطانيا' => 2, 'ألمانيا' => 2, 'تركيا' => 2, 'الهند' => 2, 'باكستان' => 1];
        $weightedCountries = [];
        foreach ($countryWeights as $country => $weight) {
            for ($w = 0; $w < $weight; $w++) {
                $weightedCountries[] = $country;
            }
        }

        $deviceTypes = ['mobile', 'mobile', 'mobile', 'desktop', 'desktop', 'tablet'];
        $browsers = ['Chrome', 'Safari', 'Firefox', 'Edge', 'Samsung Internet'];
        $platforms = ['iOS', 'Android', 'Windows', 'macOS', 'Linux'];
        $referrers = ['https://google.com', 'https://twitter.com', 'https://instagram.com', 'https://linkedin.com', null, null, null];

        $viewsData = [];
        for ($v = 0; $v < 500; $v++) {
            $card = $cards[array_rand($cards)];
            $viewedAt = Carbon::now()->subDays(rand(0, 90))->subHours(rand(0, 23));
            $viewsData[] = [
                'card_id' => $card->id,
                'ip_address' => rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0',
                'device_type' => $deviceTypes[array_rand($deviceTypes)],
                'browser' => $browsers[array_rand($browsers)],
                'platform' => $platforms[array_rand($platforms)],
                'country' => $weightedCountries[array_rand($weightedCountries)],
                'city' => $cities[array_rand($cities)],
                'referrer' => $referrers[array_rand($referrers)],
                'viewed_at' => $viewedAt,
            ];
        }
        // Insert in chunks
        foreach (array_chunk($viewsData, 100) as $chunk) {
            DB::table('card_views')->insert($chunk);
        }

        // 6. إنشاء طلبات
        $this->command->info('إنشاء 60 طلب...');
        $orderTypes = ['physical_card', 'digital_only', 'custom_design', 'bulk'];
        $statuses = ['pending', 'confirmed', 'processing', 'completed', 'completed', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'paid', 'paid', 'paid', 'failed', 'refunded'];
        $paymentMethods = ['tap', 'stc_pay', 'mada', 'visa', 'mastercard'];
        $shippingStatuses = ['pending', 'processing', 'shipped', 'delivered', 'delivered', 'delivered'];

        for ($o = 0; $o < 60; $o++) {
            $user = $users[array_rand($users)];
            $card = !empty($cards) ? $cards[array_rand($cards)] : null;
            $subtotal = [29, 49, 99, 149, 199, 299, 499][rand(0, 6)];
            $tax = round($subtotal * 0.15, 2);
            $shipping = rand(0, 1) ? [0, 15, 25, 35][rand(0, 3)] : 0;
            $discount = rand(0, 3) === 0 ? rand(5, 30) : 0;
            $total = $subtotal + $tax + $shipping - $discount;
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $createdAt = Carbon::now()->subDays(rand(0, 120));

            Order::create([
                'order_number' => 'MRF-' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'card_id' => $card?->id,
                'type' => $orderTypes[array_rand($orderTypes)],
                'quantity' => rand(1, 5),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_fee' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'status' => $status,
                'shipping_city' => $cities[array_rand($cities)],
                'shipping_status' => $shippingStatuses[array_rand($shippingStatuses)],
                'shipping_address' => 'حي ' . ['النزهة', 'الملقا', 'العليا', 'السليمانية', 'الروضة'][rand(0, 4)] . '، شارع ' . rand(1, 50),
                'shipping_postal_code' => rand(10000, 99999),
                'shipping_phone' => '05' . rand(10000000, 99999999),
                'paid_at' => $paymentStatus === 'paid' ? $createdAt->copy()->addHours(rand(1, 24)) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('');
        $this->command->info('✅ تم حقن البيانات الوهمية بنجاح!');
        $this->command->info('📊 الملخص:');
        $this->command->info('   - 50 مستخدم');
        $this->command->info('   - 12 قالب');
        $this->command->info('   - ~80 بطاقة');
        $this->command->info('   - 500 مشاهدة');
        $this->command->info('   - 60 طلب');
    }
}
