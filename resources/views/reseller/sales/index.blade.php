@extends('layouts.app')

@section('title', 'المبيعات - معروف')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">المبيعات</h1>
            <p class="text-gray-500 mt-1">{{ $reseller->store_name }}</p>
        </div>
        <button onclick="document.getElementById('newSaleModal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            تسجيل بيع جديد
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">اجمالي المبيعات</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAmount, 2) }} <span class="text-sm font-normal text-gray-500">ر.س</span></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">اجمالي العمولة</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalCommission, 2) }} <span class="text-sm font-normal text-gray-500">ر.س</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Month Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="p-4">
            <form method="GET" action="{{ route('reseller.sales.index') }}" class="flex items-center gap-4">
                <label for="month" class="text-sm font-medium text-gray-700">تصفية حسب الشهر:</label>
                <select name="month" id="month" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">كل الشهور</option>
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                    @endfor
                </select>
                <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">تصفية</button>
                @if(request('month'))
                <a href="{{ route('reseller.sales.index') }}" class="text-sm text-red-500 hover:text-red-600">مسح التصفية</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الكمية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العمولة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $sale->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $sale->sale_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($sale->quantity) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($sale->amount, 2) }} ر.س</td>
                        <td class="px-6 py-4 text-sm font-medium text-green-600">{{ number_format($sale->commission_earned, 2) }} ر.س</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">لا توجد مبيعات</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($sales->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $sales->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- New Sale Modal --}}
<div id="newSaleModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="document.getElementById('newSaleModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">تسجيل بيع جديد</h3>
                <button onclick="document.getElementById('newSaleModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('reseller.sales.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">الكمية</label>
                        <input type="number" name="quantity" id="quantity" min="1" required
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="عدد البطاقات المباعة">
                        @error('quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">المبلغ (ر.س)</label>
                        <input type="number" name="amount" id="amount" min="0" step="0.01" required
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="اجمالي مبلغ البيع">
                        @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm text-gray-500">نسبة العمولة: <span class="font-semibold text-gray-700">{{ $reseller->commission_rate }}%</span></p>
                        <p class="text-xs text-gray-400 mt-1">سيتم احتساب العمولة تلقائيا بناء على المبلغ</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition text-sm">
                        تسجيل البيع
                    </button>
                    <button type="button" onclick="document.getElementById('newSaleModal').classList.add('hidden')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg font-semibold hover:bg-gray-200 transition text-sm">
                        الغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
