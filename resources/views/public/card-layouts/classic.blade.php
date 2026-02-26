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

        $primaryColor = $designData['primaryColor'] ?? $templateConfig['colors']['primary'] ?? '#B8860B';
        $fontFamily = $designData['font'] ?? $templateConfig['font'] ?? 'Tajawal';

        $goldColor = $primaryColor;
        $goldLight = '#D4A843';
    @endphp

    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family={{ urlencode($fontFamily) }}:wght@400;500;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --cream: #FAF8F5;
            --white: #FFFFFF;
            --gold: {{ $goldColor }};
            --gold-light: {{ $goldLight }};
            --text: #1A1A1A;
            --text-2: #4A4A4A;
            --text-3: #8A8A8A;
            --border: #E8E4DF;
        }
        body {
            font-family: '{{ $fontFamily }}', 'Tajawal', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: var(--cream);
            color: var(--text);
        }

        @@keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .card-container { animation: fadeIn 0.5s ease; }

        .contact-inline-btn {
            transition: all 0.2s ease;
        }
        .contact-inline-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .info-row {
            transition: all 0.2s ease;
        }
        .info-row:hover {
            padding-right: 8px;
        }
    </style>
</head>
<body>
    <div class="card-container w-full max-w-lg">
        <div class="bg-white overflow-hidden shadow-lg" style="border-radius: 4px; border: 1px solid var(--border);">

            {{-- Gold top line --}}
            <div class="h-1" style="background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));"></div>

            {{-- Header --}}
            <div class="text-center relative" style="padding: 52px 40px 20px; border-bottom: 1px solid var(--border);">

                {{-- Profile Photo --}}
                <div class="w-32 h-32 mx-auto mb-7 rounded-full overflow-hidden flex items-center justify-center" style="background: var(--cream); border: 3px solid var(--border); box-shadow: 0 8px 24px rgba(0,0,0,0.06);">
                    @if($card->profile_image)
                        <img src="{{ asset('storage/' . $card->profile_image) }}" alt="{{ $card->full_name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-5xl" style="color: var(--text-3);">{{ mb_substr($card->full_name ?? $card->title, 0, 1) }}</span>
                    @endif
                </div>

                @if($card->logo)
                    <img src="{{ asset('storage/' . $card->logo) }}" alt="Logo" class="h-8 object-contain mx-auto mb-3">
                @endif

                <h1 class="mb-2" style="font-family: 'Playfair Display', '{{ $fontFamily }}', serif; font-size: 34px; font-weight: 700; color: var(--text); letter-spacing: -0.3px; line-height: 1.2;">
                    {{ $card->full_name ?? $card->title }}
                </h1>

                @if($card->job_title)
                    <p class="text-sm font-semibold" style="color: var(--gold); letter-spacing: 0.5px;">
                        {{ $card->job_title }}@if($card->company) &mdash; {{ $card->company }}@endif
                    </p>
                @endif

                @if($card->bio)
                    <p class="mt-4 text-sm leading-relaxed max-w-xs mx-auto" style="color: var(--text-2);">{{ $card->bio }}</p>
                @endif

                {{-- Ornament --}}
                <div class="flex items-center justify-center gap-3 py-4">
                    <div style="width: 48px; height: 1px; background: var(--gold); opacity: 0.4;"></div>
                    <div style="width: 6px; height: 6px; background: var(--gold); border-radius: 50%; opacity: 0.6;"></div>
                    <div style="width: 48px; height: 1px; background: var(--gold); opacity: 0.4;"></div>
                </div>
            </div>

            {{-- Content --}}
            <div style="padding: 28px 36px 36px;">

                {{-- Save Contact Button --}}
                <a href="{{ route('public.cards.download-vcard', $card->slug) }}"
                   class="flex items-center justify-center gap-3 w-full py-4 rounded text-white font-bold text-base transition-all mb-7" style="background: var(--text); letter-spacing: 0.5px;" onmouseover="this.style.background='#333333'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.12)';" onmouseout="this.style.background='var(--text)'; this.style.transform='none'; this.style.boxShadow='none';">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                    حفظ جهة الاتصال
                </a>

                {{-- Contact Row (inline buttons) --}}
                @if($card->phone || $card->email)
                <div class="flex gap-3 mb-7" style="flex-wrap: wrap;">
                    @if($card->phone)
                    <a href="tel:{{ $card->phone }}" class="contact-inline-btn flex-1 flex items-center justify-center gap-2 py-4 rounded text-sm font-semibold" style="border: 1.5px solid var(--border); color: var(--text); text-decoration: none; min-width: 100px;">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        اتصال
                    </a>
                    @endif
                    @if($card->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $card->whatsapp) }}" target="_blank" rel="noopener" class="contact-inline-btn flex-1 flex items-center justify-center gap-2 py-4 rounded text-sm font-semibold" style="border: 1.5px solid var(--border); color: var(--text); text-decoration: none; min-width: 100px;">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.623-1.467A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.115 0-4.116-.654-5.803-1.893l-.416-.311-2.743.87.889-2.663-.342-.433A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/></svg>
                        واتساب
                    </a>
                    @endif
                    @if($card->email)
                    <a href="mailto:{{ $card->email }}" class="contact-inline-btn flex-1 flex items-center justify-center gap-2 py-4 rounded text-sm font-semibold" style="border: 1.5px solid var(--border); color: var(--text); text-decoration: none; min-width: 100px;">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        بريد
                    </a>
                    @endif
                </div>
                @endif

                {{-- Section Title --}}
                <div class="mb-4 pb-2" style="font-family: 'Playfair Display', serif; font-size: 13px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid var(--border);">
                    التفاصيل
                </div>

                {{-- Info List --}}
                <div class="flex flex-col">
                    @if($card->phone)
                    <a href="tel:{{ $card->phone }}" class="info-row flex items-center gap-4 py-4" style="border-bottom: 1px solid rgba(0,0,0,0.04); text-decoration: none; color: var(--text);">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: var(--cream); border: 1px solid var(--border);">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold" style="color: var(--text-3); letter-spacing: 0.3px;">الهاتف</div>
                            <div class="text-sm font-semibold truncate" dir="ltr" style="color: var(--text);">{{ $card->phone }}</div>
                        </div>
                        <svg class="w-3 h-3 flex-shrink-0" style="color: var(--text-3); opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    @endif

                    @if($card->email)
                    <a href="mailto:{{ $card->email }}" class="info-row flex items-center gap-4 py-4" style="border-bottom: 1px solid rgba(0,0,0,0.04); text-decoration: none; color: var(--text);">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: var(--cream); border: 1px solid var(--border);">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold" style="color: var(--text-3); letter-spacing: 0.3px;">البريد الإلكتروني</div>
                            <div class="text-sm font-semibold truncate" dir="ltr" style="color: var(--text);">{{ $card->email }}</div>
                        </div>
                        <svg class="w-3 h-3 flex-shrink-0" style="color: var(--text-3); opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    @endif

                    @if($card->website)
                    <a href="{{ $card->website }}" target="_blank" rel="noopener" class="info-row flex items-center gap-4 py-4" style="border-bottom: 1px solid rgba(0,0,0,0.04); text-decoration: none; color: var(--text);">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: var(--cream); border: 1px solid var(--border);">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold" style="color: var(--text-3); letter-spacing: 0.3px;">الموقع</div>
                            <div class="text-sm font-semibold" style="color: var(--text);">زيارة</div>
                        </div>
                        <svg class="w-3 h-3 flex-shrink-0" style="color: var(--text-3); opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    @endif

                    @if($card->address)
                    <div class="info-row flex items-center gap-4 py-4" style="border-bottom: 1px solid rgba(0,0,0,0.04);">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: var(--cream); border: 1px solid var(--border);">
                            <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold" style="color: var(--text-3); letter-spacing: 0.3px;">العنوان</div>
                            <div class="text-sm font-semibold" style="color: var(--text);">{{ $card->address }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- Social Links --}}
                    @foreach($card->activeSocialLinks as $link)
                    @php
                        $socialIconColors = [
                            'instagram' => '#E1306C',
                            'twitter' => '#1A1A1A',
                            'linkedin' => '#0A66C2',
                            'snapchat' => '#CA8A04',
                            'youtube' => '#FF0000',
                            'tiktok' => '#1A1A1A',
                            'facebook' => '#1877F2',
                            'github' => '#1A1A1A',
                        ];
                        $iconClr = $socialIconColors[$link->platform] ?? $goldColor;
                    @endphp
                    <a href="{{ $link->url }}" target="_blank" rel="noopener" class="info-row flex items-center gap-4 py-4" style="border-bottom: 1px solid rgba(0,0,0,0.04); text-decoration: none; color: var(--text);">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: var(--cream); border: 1px solid var(--border);">
                            @switch($link->platform)
                                @case('twitter')
                                    <svg class="w-4 h-4" style="color: {{ $iconClr }};" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    @break
                                @case('instagram')
                                    <svg class="w-4 h-4" style="color: {{ $iconClr }};" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    @break
                                @case('linkedin')
                                    <svg class="w-4 h-4" style="color: {{ $iconClr }};" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    @break
                                @case('snapchat')
                                    <svg class="w-4 h-4" style="color: {{ $iconClr }};" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/></svg>
                                    @break
                                @case('youtube')
                                    <svg class="w-4 h-4" style="color: {{ $iconClr }};" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    @break
                                @case('tiktok')
                                    <svg class="w-4 h-4" style="color: {{ $iconClr }};" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                    @break
                                @default
                                    <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            @endswitch
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold" style="color: var(--text-3); letter-spacing: 0.3px;">{{ ucfirst($link->platform) }}</div>
                            <div class="text-sm font-semibold truncate" style="color: var(--text);">{{ $link->label ?? ucfirst($link->platform) }}</div>
                        </div>
                        <svg class="w-3 h-3 flex-shrink-0" style="color: var(--text-3); opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    @endforeach
                </div>

                {{-- Share Button --}}
                <button onclick="shareCard()" class="w-full mt-4 py-3 rounded text-sm font-semibold transition-all flex items-center justify-center gap-2" style="background: var(--cream); color: var(--text-2); border: 1px solid var(--border);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    مشاركة البطاقة
                </button>
            </div>

            {{-- Footer --}}
            <div class="text-center py-5" style="background: var(--cream); border-top: 1px solid var(--border);">
                <a href="{{ url('/') }}" class="text-xs" style="color: var(--text-3); text-decoration: none; font-family: 'Playfair Display', serif; font-weight: 500; letter-spacing: 1px;">معروف</a>
            </div>
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
