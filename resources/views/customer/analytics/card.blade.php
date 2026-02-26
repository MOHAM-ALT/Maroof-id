@extends('layouts.app')

@section('title', 'إحصائيات البطاقة - ' . $card->name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $card->name }}</h1>
                <p class="text-gray-600 mt-1">إحصائيات تفصيلية للبطاقة</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('customer.analytics.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; كل الإحصائيات</a>
                <a href="{{ route('customer.cards.show', $card) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">عرض البطاقة</a>
            </div>
        </div>

        <!-- Period Filter -->
        <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
            <div class="flex gap-2">
                @foreach(['7days' => '7 أيام', '30days' => '30 يوم', '90days' => '90 يوم'] as $key => $label)
                <a href="{{ route('customer.analytics.card', ['card' => $card, 'period' => $key]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium {{ $period === $key ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm text-gray-500">إجمالي المشاهدات</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">{{ number_format($analytics['total_views'] ?? 0) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm text-gray-500">مشاهدات فريدة</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ number_format($analytics['unique_views'] ?? 0) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm text-gray-500">الطلبات المدفوعة</p>
                <p class="text-3xl font-bold text-purple-600 mt-1">{{ number_format($analytics['downloads'] ?? 0) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm text-gray-500">معدل التحويل</p>
                <p class="text-3xl font-bold text-orange-600 mt-1">{{ number_format($analytics['conversion_rate'] ?? 0, 1) }}%</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Views Over Time -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">المشاهدات عبر الزمن</h3>
                <canvas id="viewsChart" height="200"></canvas>
            </div>

            <!-- Social Links Clicks -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">نقرات الروابط الاجتماعية</h3>
                @php $socialClicks = $analytics['social_clicks'] ?? []; @endphp
                @if(count($socialClicks) > 0)
                <canvas id="socialChart" height="200"></canvas>
                @else
                <p class="text-gray-500 text-center py-12">لا توجد بيانات نقرات بعد</p>
                @endif
            </div>
        </div>

        <!-- Device & Location Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Devices -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الأجهزة</h3>
                @php $devices = $analytics['devices'] ?? []; @endphp
                @if(count($devices) > 0)
                <div class="space-y-3">
                    @foreach($devices as $device)
                    @php
                        $total = array_sum(array_column($devices, 'count'));
                        $percent = $total > 0 ? round(($device['count'] / $total) * 100) : 0;
                        $deviceNames = ['mobile' => 'موبايل', 'desktop' => 'حاسوب', 'tablet' => 'تابلت'];
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700">{{ $deviceNames[$device['device_type']] ?? $device['device_type'] }}</span>
                            <span class="text-gray-500">{{ $device['count'] }} ({{ $percent }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-8">لا توجد بيانات أجهزة</p>
                @endif
            </div>

            <!-- Countries -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الدول</h3>
                @php $countries = $analytics['countries'] ?? []; @endphp
                @if(count($countries) > 0)
                <div class="space-y-3">
                    @foreach(array_slice($countries, 0, 10) as $country)
                    @php
                        $total = array_sum(array_column($countries, 'count'));
                        $percent = $total > 0 ? round(($country['count'] / $total) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700">{{ $country['country'] ?? 'غير معروف' }}</span>
                            <span class="text-gray-500">{{ $country['count'] }} ({{ $percent }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-8">لا توجد بيانات دول</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Views Chart
    const viewsData = @json($analytics['views_by_date'] ?? []);
    new Chart(document.getElementById('viewsChart'), {
        type: 'line',
        data: {
            labels: viewsData.map(d => d.date),
            datasets: [{
                label: 'المشاهدات',
                data: viewsData.map(d => d.count),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Social Clicks Chart
    const socialData = @json($analytics['social_clicks'] ?? []);
    if (socialData.length > 0) {
        new Chart(document.getElementById('socialChart'), {
            type: 'bar',
            data: {
                labels: socialData.map(d => d.platform),
                datasets: [{
                    label: 'النقرات',
                    data: socialData.map(d => d.total_clicks),
                    backgroundColor: '#8B5CF6',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });
    }
});
</script>
@endsection
