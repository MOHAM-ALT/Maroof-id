@extends('layouts.app')

@section('title', 'الإحصائيات - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">الإحصائيات</h1>
                <p class="text-gray-600 mt-1">تتبع أداء بطاقاتك ومشاهداتك</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                &larr; العودة للوحة التحكم
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">إجمالي المشاهدات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_views']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">إجمالي البطاقات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_cards']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">البطاقات النشطة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_cards']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">الطلبات المدفوعة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_downloads']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- New Users Chart -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">المستخدمون الجدد (آخر 30 يوم)</h3>
                <canvas id="usersChart" height="200"></canvas>
            </div>

            <!-- Users by Role -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">المستخدمون حسب الدور</h3>
                <canvas id="rolesChart" height="200"></canvas>
            </div>
        </div>

        <!-- Retention & Quick Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-sm text-gray-500 mb-1">المستخدمون النشطون (30 يوم)</h3>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($chartsData['active_users_last_30_days'] ?? 0) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-sm text-gray-500 mb-1">المستخدمون المشترون</h3>
                <p class="text-3xl font-bold text-green-600">{{ number_format($chartsData['purchasing_users'] ?? 0) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-sm text-gray-500 mb-1">معدل الاحتفاظ</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $chartsData['retention_rate'] ?? 0 }}%</p>
            </div>
        </div>

        <!-- Cards Performance Table -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">أداء بطاقاتك</h3>
            @php $userCards = auth()->user()->cards()->withCount('views')->orderByDesc('views_count')->get(); @endphp
            @if($userCards->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="text-right py-3 px-2">البطاقة</th>
                            <th class="text-center py-3 px-2">المشاهدات</th>
                            <th class="text-center py-3 px-2">الحالة</th>
                            <th class="text-center py-3 px-2">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userCards as $card)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-2">
                                <p class="font-medium text-gray-900">{{ $card->name }}</p>
                                <p class="text-xs text-gray-500">{{ $card->slug }}</p>
                            </td>
                            <td class="text-center py-3 px-2">
                                <span class="font-semibold text-blue-600">{{ number_format($card->views_count) }}</span>
                            </td>
                            <td class="text-center py-3 px-2">
                                @if($card->is_active)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">نشطة</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">غير نشطة</span>
                                @endif
                            </td>
                            <td class="text-center py-3 px-2">
                                <a href="{{ route('customer.analytics.card', $card) }}" class="text-blue-600 hover:text-blue-800 text-sm">التفاصيل &larr;</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500 text-center py-8">لا توجد بطاقات بعد. <a href="{{ route('customer.cards.create') }}" class="text-blue-600 hover:underline">أنشئ بطاقتك الأولى</a></p>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Users Chart
    const usersData = @json($chartsData['new_users_by_date'] ?? []);
    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: usersData.map(d => d.date),
            datasets: [{
                label: 'مستخدمون جدد',
                data: usersData.map(d => d.count),
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

    // Roles Chart
    const rolesData = @json($chartsData['users_by_role'] ?? []);
    const roleLabels = {
        'customer': 'عميل', 'super_admin': 'مدير', 'print_partner': 'شريك طباعة',
        'reseller': 'موزع', 'designer': 'مصمم', 'affiliate': 'مسوق', 'business': 'أعمال'
    };
    new Chart(document.getElementById('rolesChart'), {
        type: 'doughnut',
        data: {
            labels: rolesData.map(d => roleLabels[d.role] || d.role),
            datasets: [{
                data: rolesData.map(d => d.count),
                backgroundColor: ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#6366F1'],
            }]
        },
        options: { responsive: true }
    });
});
</script>
@endsection
