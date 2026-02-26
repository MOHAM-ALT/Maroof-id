@component('mail::message')
# {{ $campaign->subject }}

أهلاً {{ $recipientName }}،

{!! nl2br(e($body)) !!}

@component('mail::button', ['url' => url('/')])
زيارة معروف
@endcomponent

---

لإلغاء الاشتراك، قم بتعديل تفضيلات الإشعارات من إعدادات حسابك.

@endcomponent
