@extends('layouts.app')

@section('title', 'مشاركة البطاقة - ' . ($card->full_name ?? $card->title))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">مشاركة البطاقة</h1>
                <p class="text-gray-600 mt-1">{{ ($card->full_name ?? $card->title) }}</p>
            </div>
            <a href="{{ route('customer.cards.show', $card) }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; عرض البطاقة</a>
        </div>

        <!-- Direct Link -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">رابط مباشر</h3>
            <div class="flex gap-2">
                <input type="text" id="cardUrl" value="{{ $cardUrl }}" readonly
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-sm" dir="ltr">
                <button onclick="copyToClipboard('cardUrl')" class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700 transition text-sm font-medium whitespace-nowrap">
                    نسخ الرابط
                </button>
            </div>
        </div>

        <!-- QR Code -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">QR Code</h3>
            <div class="flex flex-col items-center gap-4">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($cardUrl) }}"
                     alt="QR Code" class="rounded-lg border">
                <a href="{{ route('customer.cards.download-qr', $card) }}"
                   class="bg-gray-800 text-white px-5 py-2 rounded-lg hover:bg-gray-900 transition text-sm">
                    تحميل QR Code
                </a>
            </div>
        </div>

        <!-- Social Sharing -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">مشاركة عبر وسائل التواصل</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="https://wa.me/?text={{ urlencode(($card->full_name ?? $card->title) . ' - ' . $cardUrl) }}" target="_blank"
                   class="flex items-center justify-center gap-2 bg-green-500 text-white px-4 py-3 rounded-lg hover:bg-green-600 transition text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    واتساب
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode(($card->full_name ?? $card->title)) }}&url={{ urlencode($cardUrl) }}" target="_blank"
                   class="flex items-center justify-center gap-2 bg-black text-white px-4 py-3 rounded-lg hover:bg-gray-800 transition text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    X (تويتر)
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($cardUrl) }}" target="_blank"
                   class="flex items-center justify-center gap-2 bg-blue-700 text-white px-4 py-3 rounded-lg hover:bg-blue-800 transition text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    لينكدإن
                </a>
                <a href="mailto:?subject={{ urlencode(($card->full_name ?? $card->title)) }}&body={{ urlencode('شاهد بطاقتي الرقمية: ' . $cardUrl) }}"
                   class="flex items-center justify-center gap-2 bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700 transition text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    بريد
                </a>
            </div>
        </div>

        <!-- Embed Code -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">كود التضمين (Embed)</h3>
            <p class="text-sm text-gray-500 mb-3">انسخ هذا الكود لتضمين البطاقة في موقعك</p>
            <div class="flex gap-2">
                <textarea id="embedCode" readonly rows="3"
                          class="flex-1 border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-sm font-mono" dir="ltr">{{ $embedCode }}</textarea>
                <button onclick="copyToClipboard('embedCode')" class="bg-gray-800 text-white px-5 py-3 rounded-lg hover:bg-gray-900 transition text-sm font-medium whitespace-nowrap self-start">
                    نسخ
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    const el = document.getElementById(elementId);
    el.select();
    document.execCommand('copy');

    const btn = el.nextElementSibling;
    const original = btn.textContent;
    btn.textContent = 'تم النسخ!';
    btn.classList.replace('bg-blue-600', 'bg-green-600');
    btn.classList.replace('bg-gray-800', 'bg-green-600');
    setTimeout(() => {
        btn.textContent = original;
        btn.classList.replace('bg-green-600', original.includes('رابط') ? 'bg-blue-600' : 'bg-gray-800');
    }, 2000);
}
</script>
@endsection
