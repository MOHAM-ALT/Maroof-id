<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PricingController extends Controller
{
    /**
     * Display the pricing page.
     */
    public function index(): View
    {
        $plans = [
            [
                'name' => 'المبتدئ',
                'price' => 'مجاني',
                'description' => 'مثالي للاستخدام الشخصي',
                'features' => [
                    'بطاقة رقمية واحدة',
                    '5 قوالب مجانية',
                    'رمز QR مخصص',
                    'تحليلات أساسية',
                    'دعم عبر البريد الإلكتروني',
                ],
            ],
            [
                'name' => 'الاحترافي',
                'price' => '99',
                'period' => 'ر.س / مرة واحدة للأبد',
                'description' => 'للمحترفين وأصحاب الأعمال',
                'features' => [
                    '5 بطاقات رقمية',
                    '20 قالب احترافي',
                    'رموز QR مخصصة',
                    'تحليلات متقدمة',
                    'بطاقة NFC مادية واحدة',
                    'دعم عبر البريد والدردشة',
                    'إزالة العلامة المائية',
                ],
            ],
            [
                'name' => 'الأعمال',
                'price' => '499',
                'period' => 'ر.س / مرة واحدة للأبد',
                'description' => 'للشركات والمؤسسات',
                'features' => [
                    'بطاقات غير محدودة',
                    'جميع القوالب',
                    'رموز QR مخصصة',
                    'تحليلات متقدمة وتقارير',
                    '3 بطاقات NFC مادية',
                    'دعم ذو أولوية 24/7',
                    'API للتكامل',
                    'علامة تجارية مخصصة',
                    'مدير حساب مخصص',
                ],
            ],
        ];

        return view('public.pricing', compact('plans'));
    }
}
