@extends('layouts.app')

@section('title', 'برنامج الأفلييت - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">برنامج الأفلييت</h1>
            <p class="text-gray-600 mt-1">تتبع إحالاتك وأرباحك</p>
            <span class="text-xs px-3 py-1 rounded-full mt-2 inline-block {{ $affiliate->status->value === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ $affiliate->status->value === 'active' ? 'نشط' : 'غير نشط' }}
            </span>
        </div>

        <!-- Referral Link -->
        <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">رابط الإحالة الخاص بك</label>
            <div class="flex gap-2">
                <input type="text" id="referralLink" value="{{ $referralLink }}" readonly
                       class="flex-1 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-700" dir="ltr">
                <button onclick="copyLink()" id="copyBtn"
                        class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2.5 rounded-lg font-semibold transition text-sm">
                    نسخ
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-2">شارك هذا الرابط واحصل على عمولة {{ $commissionRate }}% من كل عملية شراء</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">إجمالي النقرات</p>
                <p class="text-2xl font-bold text-cyan-600 mt-1">{{ number_format($totalClicks) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">التحويلات</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalConversions) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">نسبة التحويل</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $conversionRate }}%</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">الأرباح</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalEarnings, 0) }} <span class="text-xs">ر.س</span></p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">نقرات الشهر</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($monthlyClicks) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">تحويلات الشهر</p>
                <p class="text-2xl font-bold text-orange-600 mt-1">{{ number_format($monthlyConversions) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Clicks -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">آخر النقرات</h2>
                    <a href="{{ route('affiliate.clicks.index') }}" class="text-cyan-600 hover:text-cyan-700 text-sm font-semibold">عرض الكل</a>
                </div>

                @if($recentClicks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-500 border-b">
                                <th class="text-right py-2 font-medium">التاريخ</th>
                                <th class="text-right py-2 font-medium">الدولة</th>
                                <th class="text-right py-2 font-medium">المصدر</th>
                                <th class="text-center py-2 font-medium">تحويل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentClicks as $click)
                            <tr class="border-b last:border-0">
                                <td class="py-2 text-gray-600">{{ $click->clicked_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td class="py-2 text-gray-600">{{ $click->visitor_country ?? '-' }}</td>
                                <td class="py-2 text-gray-500 text-xs truncate max-w-[150px]" dir="ltr">{{ $click->source_url ?? '-' }}</td>
                                <td class="py-2 text-center">
                                    @if($click->converted)
                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">نعم</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">لا</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center text-gray-500 py-8">لا توجد نقرات بعد. شارك رابط الإحالة الخاص بك!</p>
                @endif
            </div>

            <!-- Top Countries -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">أكثر الدول</h2>
                @if($topCountries->count() > 0)
                <div class="space-y-3">
                    @foreach($topCountries as $country)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 text-sm">{{ $country->visitor_country }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-cyan-500 h-2 rounded-full" style="width: {{ $totalClicks > 0 ? ($country->count / $totalClicks * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-8 text-left">{{ $country->count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-gray-500 py-4 text-sm">لا توجد بيانات</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyLink() {
    const input = document.getElementById('referralLink');
    input.select();
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.textContent = 'تم النسخ!';
        btn.classList.replace('bg-cyan-600', 'bg-green-600');
        btn.classList.replace('hover:bg-cyan-700', 'hover:bg-green-700');
        setTimeout(() => {
            btn.textContent = 'نسخ';
            btn.classList.replace('bg-green-600', 'bg-cyan-600');
            btn.classList.replace('hover:bg-green-700', 'hover:bg-cyan-700');
        }, 2000);
    });
}
</script>
@endsection
