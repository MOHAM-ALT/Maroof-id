<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة محمية - معروف</title>
    @vite(['resources/css/app.css'])
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-container { animation: fadeInUp 0.6s ease-out; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-8 px-4">
    <div class="w-full max-w-md card-container">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="h-32 bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <div class="px-6 py-8 text-center">
                <h1 class="text-xl font-bold text-gray-900 mb-2">بطاقة محمية بكلمة مرور</h1>
                <p class="text-gray-500 text-sm mb-6">أدخل كلمة المرور للوصول إلى هذه البطاقة</p>

                @if($card->full_name || $card->title)
                <div class="mb-6 bg-gray-50 rounded-xl p-4">
                    <p class="font-semibold text-gray-800">{{ $card->full_name ?? $card->title }}</p>
                    @if($card->job_title)
                        <p class="text-gray-500 text-sm">{{ $card->job_title }}</p>
                    @endif
                </div>
                @endif

                <form method="POST" action="{{ route('public.cards.unlock', $card->slug) }}">
                    @csrf
                    <div class="mb-4">
                        <input type="password" name="password" required autofocus
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 text-center text-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('password') border-red-500 @enderror"
                               placeholder="كلمة المرور">
                        @error('password')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white rounded-xl py-3 font-bold transition shadow-sm">
                        فتح البطاقة
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-gray-400 text-xs mt-6">
            بطاقة رقمية من <a href="{{ url('/') }}" class="text-blue-500 hover:underline">معروف</a>
        </p>
    </div>
</body>
</html>
