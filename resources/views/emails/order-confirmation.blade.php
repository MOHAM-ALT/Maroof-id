@component('mail::message')
# تأكيد الطلب 📦

شكراً {{ $order->user->name }}!

تم استقبال طلبك بنجاح. إليك تفاصيلك:

---

## معلومات الطلب

**رقم الطلب:** {{ $order->order_number }}  
**التاريخ:** {{ $order->created_at->format('d/m/Y H:i') }}  
**النوع:** 
@switch($order->type)
    @case('standard')
        بطاقات عادية
        @break
    @case('premium')
        بطاقات فاخرة
        @break
    @case('custom')
        بطاقات مخصصة
        @break
@endswitch

---

## تفاصيل الدفع

| البيان | المبلغ |
|------|-------|
| **الإجمالي الفرعي** | {{ number_format($order->subtotal, 2) }} ر.س |
| **الضريبة (15%)** | {{ number_format($order->tax, 2) }} ر.س |
| **الشحن** | {{ number_format($order->shipping, 2) }} ر.س |
@if($order->coupon)
| **خصم الكوبون** | -{{ number_format($order->discount, 2) }} ر.س |
@endif
| **المجموع النهائي** | **{{ number_format($order->total, 2) }} ر.س** |

@if($order->coupon)
**الكوبون المطبق:** {{ $order->coupon->code }}
@endif

---

## حالة الدفع

**الحالة:** 
@if($order->payment_status === 'pending')
قيد الانتظار ⏳
@elseif($order->payment_status === 'completed')
مكتمل ✅
@else
{{ $order->payment_status }}
@endif

**طريقة الدفع:** {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}

---

### الخطوات التالية:

1. سيتم مراجعة طلبك خلال 24 ساعة
2. سيتم إرسال البطاقات إلى شركائنا للطباعة
3. ستصل البطاقات إلى عنوانك خلال 5-7 أيام عمل

@component('mail::button', ['url' => $orderUrl])
عرض الطلب
@endcomponent

---

**شكراً لاختيارك MAROOF!**

للمزيد من المساعدة: support@maroof.app

@endcomponent
