@extends('layouts.app')

@section('title', 'تقرير المبيعات - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">تقرير المبيعات</h1>
                <p class="text-gray-600 mt-1">{{ $report['start_date'] ?? '' }} - {{ $report['end_date'] ?? '' }}</p>
            </div>
            <a href="{{ route('customer.analytics.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; الإحصائيات</a>
        </div>

        <!-- Period Filter -->
        <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
            <div class="flex gap-2">
                @foreach(['week' => 'أسبوع', 'month' => 'شهر', 'quarter' => 'ربع سنة', 'year' => 'سنة'] as $key => $label)
                <a href="{{ route('customer.analytics.sales-report', ['period' => $key]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium {{ $period === $key ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm text-gray-500">إجمالي الطلبات</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">{{ number_format($report['total_orders'] ?? 0) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm text-gray-500">متوسط قيمة الطلب</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ number_format($report['average_order_value'] ?? 0, 2) }} <span class="text-sm font-normal">ر.س</span></p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm text-gray-500">إجمالي الإيرادات</p>
                <p class="text-3xl font-bold text-purple-600 mt-1">{{ number_format($report['total_revenue'] ?? 0, 2) }} <span class="text-sm font-normal">ر.س</span></p>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">الإيرادات حسب التاريخ</h3>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">الطلبات حسب التاريخ</h3>
            @php $ordersByDate = $report['orders_by_date'] ?? []; @endphp
            @if(count($ordersByDate) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="text-right py-3 px-2">التاريخ</th>
                            <th class="text-center py-3 px-2">عدد الطلبات</th>
                            <th class="text-center py-3 px-2">الإيرادات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ordersByDate as $row)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-2 text-gray-700">{{ $row['date'] }}</td>
                            <td class="text-center py-3 px-2 font-medium">{{ $row['count'] }}</td>
                            <td class="text-center py-3 px-2 font-medium text-green-600">{{ number_format($row['revenue'], 2) }} ر.س</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500 text-center py-8">لا توجد طلبات في هذه الفترة</p>
            @endif
        </div>

        <!-- Top Cards -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">أكثر البطاقات مبيعاً</h3>
            @php $topTemplates = $report['top_templates'] ?? []; @endphp
            @if(count($topTemplates) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="text-right py-3 px-2">#</th>
                            <th class="text-right py-3 px-2">البطاقة</th>
                            <th class="text-center py-3 px-2">المبيعات</th>
                            <th class="text-center py-3 px-2">الإيرادات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topTemplates as $i => $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-2 text-gray-500">{{ $i + 1 }}</td>
                            <td class="py-3 px-2 text-gray-700">بطاقة #{{ $item['card_id'] }}</td>
                            <td class="text-center py-3 px-2 font-medium">{{ $item['sales_count'] }}</td>
                            <td class="text-center py-3 px-2 font-medium text-green-600">{{ number_format($item['total_revenue'], 2) }} ر.س</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500 text-center py-8">لا توجد بيانات مبيعات</p>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ordersData = @json($report['orders_by_date'] ?? []);
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: ordersData.map(d => d.date),
            datasets: [{
                label: 'الإيرادات (ر.س)',
                data: ordersData.map(d => d.revenue),
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endsection
