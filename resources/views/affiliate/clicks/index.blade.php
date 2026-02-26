@extends('layouts.app')

@section('title', 'النقرات - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">سجل النقرات</h1>
            <p class="text-gray-600 mt-1">تتبع جميع النقرات على رابط الإحالة</p>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border p-4 text-center">
                <p class="text-2xl font-bold text-cyan-600">{{ number_format($totalClicks) }}</p>
                <p class="text-gray-500 text-xs">إجمالي النقرات</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($conversions) }}</p>
                <p class="text-gray-500 text-xs">التحويلات</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $totalClicks > 0 ? round(($conversions / $totalClicks) * 100, 1) : 0 }}%</p>
                <p class="text-gray-500 text-xs">نسبة التحويل</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
            <div class="flex gap-2">
                <a href="{{ route('affiliate.clicks.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold {{ !request('converted') ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">الكل</a>
                <a href="{{ route('affiliate.clicks.index', ['converted' => 'yes']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('converted') === 'yes' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">محوّلة</a>
                <a href="{{ route('affiliate.clicks.index', ['converted' => 'no']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('converted') === 'no' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">غير محوّلة</a>
            </div>
        </div>

        <!-- Clicks Table -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-right px-4 py-3 font-medium text-gray-500">التاريخ</th>
                            <th class="text-right px-4 py-3 font-medium text-gray-500">IP</th>
                            <th class="text-right px-4 py-3 font-medium text-gray-500">الدولة</th>
                            <th class="text-right px-4 py-3 font-medium text-gray-500">المصدر</th>
                            <th class="text-center px-4 py-3 font-medium text-gray-500">تحويل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clicks as $click)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">{{ $click->clicked_at?->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500" dir="ltr">
                                @php
                                    $ip = $click->visitor_ip ?? '-';
                                    if ($ip !== '-') {
                                        $parts = explode('.', $ip);
                                        if (count($parts) === 4) $ip = $parts[0] . '.' . $parts[1] . '.' . $parts[2] . '.***';
                                    }
                                @endphp
                                {{ $ip }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $click->visitor_country ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs truncate max-w-[200px]" dir="ltr">{{ $click->source_url ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($click->converted)
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">تم التحويل</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">لم يتحول</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-500">لا توجد نقرات</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($clicks->hasPages())
        <div class="mt-6">{{ $clicks->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
