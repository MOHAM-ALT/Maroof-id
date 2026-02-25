@extends('layouts.app')

@section('title', 'طلب طباعة جديد')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <div class="mb-8">
            <a href="{{ route('customer.orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm mb-2 inline-block">→ العودة للطلبات</a>
            <h1 class="text-3xl font-bold text-gray-900">طلب طباعة جديد</h1>
            <p class="text-gray-600 mt-1">اطلب طباعة بطاقتك الرقمية كبطاقة NFC فعلية</p>
        </div>

        <form method="POST" action="{{ route('customer.orders.store') }}" class="space-y-6">
            @csrf

            <!-- Card Selection -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">اختر البطاقة</h2>

                @if($cards->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <p class="mb-3">لا توجد بطاقات بعد</p>
                        <a href="{{ route('customer.cards.create') }}" class="text-blue-600 font-semibold hover:underline">أنشئ بطاقتك الأولى</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($cards as $card)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="card_id" value="{{ $card->id }}"
                                   data-template="{{ $card->template_id }}"
                                   class="peer hidden" {{ (old('card_id', request('card_id')) == $card->id) ? 'checked' : '' }} required>
                            <div class="border-2 border-gray-200 rounded-lg p-4 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 group-hover:border-blue-300">
                                <h3 class="font-bold text-gray-900">{{ $card->title }}</h3>
                                <p class="text-gray-500 text-sm mt-1">{{ $card->full_name ?? 'بدون اسم' }}</p>
                                @if($card->template)
                                    <p class="text-blue-600 text-xs mt-1">قالب: {{ $card->template->name }}</p>
                                @endif
                            </div>
                            <div class="absolute top-3 left-3 w-6 h-6 rounded-full bg-blue-500 text-white items-center justify-center text-xs hidden peer-checked:flex">✓</div>
                        </label>
                        @endforeach
                    </div>
                @endif
                @error('card_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            <!-- Quantity & Type -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">تفاصيل الطلب</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">نوع الطباعة</label>
                        <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500" id="orderType">
                            <option value="physical_card" {{ old('type') == 'physical_card' ? 'selected' : '' }}>بطاقة NFC عادية (99 ر.س)</option>
                            <option value="custom_design" {{ old('type') == 'custom_design' ? 'selected' : '' }}>بطاقة NFC تصميم خاص (199 ر.س)</option>
                            <option value="bulk" {{ old('type') == 'bulk' ? 'selected' : '' }}>طلب كمية (79 ر.س/بطاقة)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">الكمية</label>
                        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="1000" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500" id="orderQuantity">
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">معلومات الشحن</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">العنوان *</label>
                        <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500"
                               placeholder="الحي، الشارع، رقم المبنى">
                        @error('shipping_address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">المدينة *</label>
                        <select name="shipping_city" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                            <option value="">اختر المدينة</option>
                            @foreach(['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'الظهران', 'تبوك', 'أبها', 'الطائف', 'بريدة', 'نجران', 'جازان', 'ينبع', 'حائل', 'الجبيل', 'خميس مشيط', 'الأحساء'] as $city)
                                <option value="{{ $city }}" {{ old('shipping_city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                        @error('shipping_city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">الرمز البريدي</label>
                        <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" dir="ltr"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 text-left"
                               placeholder="12345">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">رقم الجوال للشحن *</label>
                        <input type="tel" name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" required dir="ltr"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 text-left"
                               placeholder="+966 5xx xxx xxx">
                        @error('shipping_phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">ملاحظات (اختياري)</label>
                        <input type="text" name="notes" value="{{ old('notes') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500"
                               placeholder="ملاحظات إضافية">
                    </div>
                </div>
            </div>

            <!-- Coupon -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">كوبون خصم</h2>
                <div class="flex gap-3">
                    <input type="text" name="coupon_code" value="{{ old('coupon_code') }}"
                           class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500"
                           placeholder="أدخل كود الخصم">
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">ملخص الطلب</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-600">سعر البطاقة</span><span class="font-semibold" id="unitPrice">99.00 ر.س</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">الكمية</span><span class="font-semibold" id="displayQuantity">1</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">المجموع الفرعي</span><span class="font-semibold" id="subtotalDisplay">99.00 ر.س</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">ضريبة القيمة المضافة (15%)</span><span class="font-semibold" id="taxDisplay">14.85 ر.س</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">الشحن</span><span class="font-semibold" id="shippingDisplay">25.00 ر.س</span></div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="text-gray-900 font-bold text-base">الإجمالي</span>
                        <span class="text-blue-600 font-bold text-xl" id="totalDisplay">138.85 ر.س</span>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold transition shadow-sm" {{ $cards->isEmpty() ? 'disabled' : '' }}>
                    متابعة للدفع
                </button>
                <a href="{{ route('customer.orders.index') }}" class="text-gray-600 hover:text-gray-800 px-6 py-3">إلغاء</a>
            </div>
        </form>
    </div>
</div>

<script>
const prices = { physical_card: 99, custom_design: 199, bulk: 79 };
const taxRate = 0.15;
const shippingFee = 25;

function updateSummary() {
    const type = document.getElementById('orderType').value;
    const qty = parseInt(document.getElementById('orderQuantity').value) || 1;
    const unit = prices[type] || 99;
    const subtotal = unit * qty;
    const tax = subtotal * taxRate;
    const total = subtotal + tax + shippingFee;

    document.getElementById('unitPrice').textContent = unit.toFixed(2) + ' ر.س';
    document.getElementById('displayQuantity').textContent = qty;
    document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2) + ' ر.س';
    document.getElementById('taxDisplay').textContent = tax.toFixed(2) + ' ر.س';
    document.getElementById('shippingDisplay').textContent = shippingFee.toFixed(2) + ' ر.س';
    document.getElementById('totalDisplay').textContent = total.toFixed(2) + ' ر.س';
}

document.getElementById('orderType').addEventListener('change', updateSummary);
document.getElementById('orderQuantity').addEventListener('input', updateSummary);
updateSummary();
</script>
@endsection
