<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $card->meta_title ?? ($card->full_name ?? $card->title) . ' - معروف' }}</title>
    <meta name="description" content="{{ $card->meta_description ?? $card->bio ?? ($card->job_title ? $card->job_title . ' في ' . ($card->company ?? 'معروف') : 'بطاقة رقمية احترافية من معروف') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('public.cards.show', $card->slug) }}">

    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ $card->full_name ?? $card->title }}">
    <meta property="og:description" content="{{ $card->job_title ? $card->job_title . ($card->company ? ' - ' . $card->company : '') : ($card->bio ?? 'بطاقة رقمية احترافية') }}">
    <meta property="og:url" content="{{ route('public.cards.show', $card->slug) }}">
    <meta property="og:site_name" content="معروف">
    @if($card->profile_image)
    <meta property="og:image" content="{{ asset('storage/' . $card->profile_image) }}">
    @endif
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $card->full_name ?? $card->title }}">
    <meta name="twitter:description" content="{{ $card->job_title ? $card->job_title . ($card->company ? ' - ' . $card->company : '') : ($card->bio ?? 'بطاقة رقمية احترافية') }}">
    @if($card->profile_image)
    <meta name="twitter:image" content="{{ asset('storage/' . $card->profile_image) }}">
    @endif

    <script type="application/ld+json">
    @php
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $card->full_name ?? $card->title,
        ];
        if ($card->job_title) $jsonLd['jobTitle'] = $card->job_title;
        if ($card->company) $jsonLd['worksFor'] = ['@type' => 'Organization', 'name' => $card->company];
        if ($card->email) $jsonLd['email'] = $card->email;
        if ($card->phone) $jsonLd['telephone'] = $card->phone;
        if ($card->website) $jsonLd['url'] = $card->website;
        if ($card->profile_image) $jsonLd['image'] = asset('storage/' . $card->profile_image);
        if ($card->bio) $jsonLd['description'] = $card->bio;
        $jsonLd['mainEntityOfPage'] = route('public.cards.show', $card->slug);
    @endphp
    {!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    @php
        $designData = $card->design_data;
        if (is_string($designData)) $designData = json_decode($designData, true);
        $templateConfig = $card->template?->design_config ?? [];

        $primaryColor = $designData['primaryColor'] ?? $templateConfig['colors']['primary'] ?? '#667eea';
        $fontFamily = $designData['font'] ?? $templateConfig['font'] ?? 'Cairo';

        $r = hexdec(substr($primaryColor, 1, 2));
        $g = hexdec(substr($primaryColor, 3, 2));
        $b = hexdec(substr($primaryColor, 5, 2));
        $secondaryColor = $templateConfig['colors']['secondary'] ?? sprintf('#%02x%02x%02x', min(255, $r + 40), min(255, $g + 20), min(255, $b + 30));
    @endphp

    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontFamily) }}:wght@400;600;700;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: '{{ $fontFamily }}', 'Cairo', sans-serif; margin: 0; padding: 0; }
        .gradient-bg { background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%); }

        @@keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @@keyframes scaleIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .animate-up { animation: fadeInUp 0.5s ease-out both; }
        .animate-scale { animation: scaleIn 0.4s ease-out both; }

        .contact-card {
            transition: all 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, {{ $primaryColor }}08, {{ $secondaryColor }}08, {{ $primaryColor }}05);">

    <div class="w-full max-w-md">

        {{-- Gradient Header --}}
        <div class="gradient-bg rounded-t-3xl p-8 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full -mr-32 -mt-32" style="background: rgba(255,255,255,0.1);"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full -ml-24 -mb-24" style="background: rgba(255,255,255,0.1);"></div>

            <div class="relative z-10">
                {{-- Profile Photo --}}
                <div class="w-32 h-32 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-2xl ring-4 ring-white/30 overflow-hidden animate-scale">
                    @if($card->profile_image)
                        <img src="{{ asset('storage/' . $card->profile_image) }}" alt="{{ $card->full_name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-5xl font-bold" style="color: {{ $primaryColor }};">{{ mb_substr($card->full_name ?? $card->title, 0, 1) }}</span>
                    @endif
                </div>

                <h1 class="text-3xl font-black text-white mb-2 animate-up">{{ $card->full_name ?? $card->title }}</h1>
                @if($card->job_title)
                    <p class="text-white/90 text-lg font-semibold animate-up" style="animation-delay:0.1s;">{{ $card->job_title }}</p>
                @endif
                @if($card->company)
                    <p class="text-white/70 text-sm animate-up" style="animation-delay:0.15s;">{{ $card->company }}</p>
                @endif
            </div>
        </div>

        {{-- Body - Contact Cards --}}
        <div class="bg-white rounded-b-3xl shadow-2xl p-6 space-y-3">

            @if($card->bio)
            <div class="text-gray-600 text-sm leading-relaxed text-center mb-4 animate-up" style="animation-delay:0.2s;">
                {{ $card->bio }}
            </div>
            @endif

            {{-- Phone --}}
            @if($card->phone)
            <a href="tel:{{ $card->phone }}"
               class="contact-card flex items-center gap-4 p-4 rounded-2xl border animate-up" style="background: linear-gradient(135deg, #dcfce7, #d1fae5); border-color: #bbf7d0; animation-delay:0.25s;">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-gray-500 text-sm font-semibold">اتصل بي</p>
                    <p class="text-gray-900 font-bold text-lg" dir="ltr">{{ $card->phone }}</p>
                </div>
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            @endif

            {{-- WhatsApp --}}
            @if($card->whatsapp)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $card->whatsapp) }}" target="_blank" rel="noopener"
               class="contact-card flex items-center gap-4 p-4 rounded-2xl border animate-up" style="background: linear-gradient(135deg, #d1fae5, #ccfbf1); border-color: #a7f3d0; animation-delay:0.3s;">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.623-1.467A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.115 0-4.116-.654-5.803-1.893l-.416-.311-2.743.87.889-2.663-.342-.433A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/></svg>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-gray-500 text-sm font-semibold">واتساب</p>
                    <p class="text-gray-900 font-bold">راسلني</p>
                </div>
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            @endif

            {{-- Email --}}
            @if($card->email)
            <a href="mailto:{{ $card->email }}"
               class="contact-card flex items-center gap-4 p-4 rounded-2xl border animate-up" style="background: linear-gradient(135deg, #fee2e2, #fce7f3); border-color: #fecaca; animation-delay:0.35s;">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0" style="background: linear-gradient(135deg, #ef4444, #ec4899);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-gray-500 text-sm font-semibold">البريد الإلكتروني</p>
                    <p class="text-gray-900 font-semibold text-sm break-all" dir="ltr">{{ $card->email }}</p>
                </div>
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            @endif

            {{-- Website --}}
            @if($card->website)
            <a href="{{ $card->website }}" target="_blank" rel="noopener"
               class="contact-card flex items-center gap-4 p-4 rounded-2xl border animate-up" style="background: linear-gradient(135deg, #cffafe, #dbeafe); border-color: #a5f3fc; animation-delay:0.4s;">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0" style="background: linear-gradient(135deg, #06b6d4, #3b82f6);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-gray-500 text-sm font-semibold">الموقع الإلكتروني</p>
                    <p class="text-gray-900 font-bold">زيارة</p>
                </div>
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            @endif

            {{-- Address --}}
            @if($card->address)
            <div class="contact-card flex items-center gap-4 p-4 rounded-2xl border animate-up" style="background: linear-gradient(135deg, #e0e7ff, #ede9fe); border-color: #c7d2fe; animation-delay:0.45s;">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-gray-500 text-sm font-semibold">الموقع</p>
                    <p class="text-gray-900 font-bold text-sm">{{ $card->address }}</p>
                </div>
            </div>
            @endif

            {{-- Social Links as colorful cards --}}
            @if($card->activeSocialLinks->count() > 0)
                @foreach($card->activeSocialLinks as $index => $link)
                @php
                    $socialColors = [
                        'instagram' => ['from' => '#ec4899', 'to' => '#8b5cf6', 'bg_from' => '#fce7f3', 'bg_to' => '#ede9fe', 'border' => '#f9a8d4'],
                        'twitter' => ['from' => '#1f2937', 'to' => '#374151', 'bg_from' => '#f3f4f6', 'bg_to' => '#e5e7eb', 'border' => '#d1d5db'],
                        'linkedin' => ['from' => '#2563eb', 'to' => '#4f46e5', 'bg_from' => '#dbeafe', 'bg_to' => '#e0e7ff', 'border' => '#bfdbfe'],
                        'snapchat' => ['from' => '#eab308', 'to' => '#f59e0b', 'bg_from' => '#fef9c3', 'bg_to' => '#fef3c7', 'border' => '#fde68a'],
                        'youtube' => ['from' => '#ef4444', 'to' => '#dc2626', 'bg_from' => '#fee2e2', 'bg_to' => '#fecaca', 'border' => '#fca5a5'],
                        'tiktok' => ['from' => '#111827', 'to' => '#1f2937', 'bg_from' => '#f3f4f6', 'bg_to' => '#e5e7eb', 'border' => '#d1d5db'],
                        'facebook' => ['from' => '#2563eb', 'to' => '#1d4ed8', 'bg_from' => '#dbeafe', 'bg_to' => '#bfdbfe', 'border' => '#93c5fd'],
                        'github' => ['from' => '#1f2937', 'to' => '#111827', 'bg_from' => '#f3f4f6', 'bg_to' => '#e5e7eb', 'border' => '#d1d5db'],
                    ];
                    $sc = $socialColors[$link->platform] ?? ['from' => $primaryColor, 'to' => $secondaryColor, 'bg_from' => "{$primaryColor}15", 'bg_to' => "{$secondaryColor}15", 'border' => "{$primaryColor}30"];
                    $delay = 0.5 + ($index * 0.05);
                @endphp
                <a href="{{ $link->url }}" target="_blank" rel="noopener"
                   class="contact-card flex items-center gap-4 p-4 rounded-2xl border animate-up" style="background: linear-gradient(135deg, {{ $sc['bg_from'] }}, {{ $sc['bg_to'] }}); border-color: {{ $sc['border'] }}; animation-delay:{{ $delay }}s;">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0" style="background: linear-gradient(135deg, {{ $sc['from'] }}, {{ $sc['to'] }});">
                        @switch($link->platform)
                            @case('twitter')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                @break
                            @case('instagram')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                @break
                            @case('linkedin')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                @break
                            @case('snapchat')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/></svg>
                                @break
                            @case('youtube')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                @break
                            @case('tiktok')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                @break
                            @default
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        @endswitch
                    </div>
                    <div class="flex-1 text-right">
                        <p class="text-gray-500 text-sm font-semibold">{{ ucfirst($link->platform) }}</p>
                        <p class="text-gray-900 font-bold">{{ $link->label ?? ucfirst($link->platform) }}</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                @endforeach
            @endif

            {{-- Save Contact Button --}}
            <a href="{{ route('public.cards.download-vcard', $card->slug) }}"
               class="gradient-bg w-full text-white font-bold py-4 rounded-2xl hover:shadow-2xl transition-all flex items-center justify-center gap-2 mt-4 animate-up" style="animation-delay:0.7s;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                حفظ جهة الاتصال
            </a>

            {{-- Share Button --}}
            <button onclick="shareCard()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl py-3 text-center font-semibold transition flex items-center justify-center gap-2 text-sm animate-up" style="animation-delay:0.75s;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                مشاركة البطاقة
            </button>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6 pb-4">
            <p class="text-gray-500 text-sm">بطاقة رقمية من <a href="{{ url('/') }}" style="color: {{ $primaryColor }}; font-weight: 700; text-decoration: none;">معروف</a></p>
        </div>
    </div>

    <script>
    function shareCard() {
        const shareData = {
            title: {!! json_encode($card->full_name ?? $card->title) !!},
            text: {!! json_encode(($card->job_title ?? '') . ($card->company ? ' - ' . $card->company : '')) !!},
            url: window.location.href
        };
        if (navigator.share) {
            navigator.share(shareData);
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const btn = event.target.closest('button');
                const original = btn.innerHTML;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> تم نسخ الرابط!';
                btn.classList.add('bg-green-100', 'text-green-700');
                setTimeout(() => { btn.innerHTML = original; btn.classList.remove('bg-green-100', 'text-green-700'); }, 2000);
            });
        }
    }
    </script>
</body>
</html>