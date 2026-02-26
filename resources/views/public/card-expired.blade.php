<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة منتهية الصلاحية - معروف</title>
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
            <div class="h-32 bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <div class="px-6 py-8 text-center">
                <h1 class="text-xl font-bold text-gray-900 mb-2">بطاقة منتهية الصلاحية</h1>
                <p class="text-gray-500 text-sm mb-6">هذه البطاقة لم تعد متاحة للعرض</p>

                @if($card->full_name || $card->title)
                <div class="mb-6 bg-gray-50 rounded-xl p-4">
                    <p class="font-semibold text-gray-800">{{ $card->full_name ?? $card->title }}</p>
                    @if($card->job_title)
                        <p class="text-gray-500 text-sm">{{ $card->job_title }}</p>
                    @endif
                </div>
                @endif

                @if($card->expires_at)
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <p class="text-red-600 text-sm">
                        انتهت صلاحية هذه البطاقة في
                        <span class="font-bold">{{ $card->expires_at->format('d/m/Y') }}</span>
                    </p>
                </div>
                @endif

                <a href="{{ url('/') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-8 py-3 font-bold transition shadow-sm">
                    الذهاب للرئيسية
                </a>
            </div>
        </div>

        <p class="text-center text-gray-400 text-xs mt-6">
            بطاقة رقمية من <a href="{{ url('/') }}" class="text-blue-500 hover:underline">معروف</a>
        </p>
    </div>
</body>
</html>
