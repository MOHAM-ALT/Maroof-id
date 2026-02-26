@extends('layouts.auth')

@section('title', 'تأكيد البريد الإلكتروني - معروف')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">معروف</h1>
            <div class="mt-4">
                <svg class="w-16 h-16 mx-auto text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mt-4">تأكيد البريد الإلكتروني</h2>
            <p class="text-gray-500 mt-2 text-sm">شكراً لتسجيلك! يرجى التحقق من بريدك الإلكتروني بالضغط على الرابط الذي أرسلناه لك.</p>
        </div>

        @if (session('resent'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <p>تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني.</p>
            </div>
        @endif

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-700 text-sm">
                إذا لم تستلم البريد الإلكتروني، تحقق من مجلد البريد غير المرغوب فيه (Spam) أو اضغط الزر أدناه لإعادة الإرسال.
            </p>
        </div>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                إعادة إرسال رابط التأكيد
            </button>
        </form>

        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-red-600 text-sm transition">
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
