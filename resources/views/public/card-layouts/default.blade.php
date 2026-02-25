<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $card->meta_title ?? ($card->full_name ?? $card->title) . ' - معروف' }}</title>
    <meta name="description" content="{{ $card->meta_description ?? $card->bio ?? ($card->job_title ? $card->job_title . ' في ' . ($card->company ?? 'معروف') : 'بطاقة رقمية احترافية من معروف') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('public.cards.show', $card->slug) }}">

    <!-- Open Graph -->
    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ $card->full_name ?? $card->title }}">
    <meta property="og:description" content="{{ $card->job_title ? $card->job_title . ($card->company ? ' - ' . $card->company : '') : ($card->bio ?? 'بطاقة رقمية احترافية') }}">
    <meta property="og:url" content="{{ route('public.cards.show', $card->slug) }}">
    <meta property="og:site_name" content="معروف">
    @if($card->profile_image)
    <meta property="og:image" content="{{ asset('storage/' . $card->profile_image) }}">
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $card->full_name ?? $card->title }}">
    <meta name="twitter:description" content="{{ $card->job_title ? $card->job_title . ($card->company ? ' - ' . $card->company : '') : ($card->bio ?? 'بطاقة رقمية احترافية') }}">
    @if($card->profile_image)
    <meta name="twitter:image" content="{{ asset('storage/' . $card->profile_image) }}">
    @endif

    <!-- Structured Data (JSON-LD) -->
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
        if (is_string($designData)) {
            $designData = json_decode($designData, true);
        }
        $templateConfig = $card->template?->design_config ?? [];

        $primaryColor = $designData['primaryColor']
            ?? $templateConfig['colors']['primary']
            ?? '#2563EB';
        $bgStyle = $designData['bgStyle'] ?? 'gradient';
        $fontFamily = $designData['font']
            ?? $templateConfig['font']
            ?? 'Cairo';
        $borderRadius = ($designData['borderRadius'] ?? $templateConfig['styles']['borderRadius'] ?? '16') . 'px';
        $btnStyle = $designData['btnStyle'] ?? $templateConfig['styles']['btnStyle'] ?? 'outline';

        $r = hexdec(substr($primaryColor, 1, 2));
        $g = hexdec(substr($primaryColor, 3, 2));
        $b = hexdec(substr($primaryColor, 5, 2));
        $secondaryColor = $templateConfig['colors']['secondary']
            ?? sprintf('#%02x%02x%02x', min(255, $r + 50), min(255, $g + 50), min(255, $b + 50));
        $accentColor = $templateConfig['colors']['accent'] ?? $primaryColor;
        $bgColor = $templateConfig['colors']['background'] ?? '#FFFFFF';
        $textColor = $templateConfig['colors']['text'] ?? '#1F2937';

        if ($bgStyle === 'solid') {
            $coverBg = "background: {$primaryColor};";
        } elseif ($bgStyle === 'dark') {
            $coverBg = "background: linear-gradient(135deg, #1f2937, {$primaryColor});";
        } elseif ($bgStyle === 'pattern') {
            $coverBg = "background: repeating-linear-gradient(45deg, {$primaryColor}, {$primaryColor} 10px, {$secondaryColor} 10px, {$secondaryColor} 20px);";
        } else {
            $coverBg = "background: linear-gradient(135deg, {$primaryColor}, {$secondaryColor});";
        }
    @endphp

    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontFamily) }}:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --card-primary: {{ $primaryColor }};
            --card-secondary: {{ $secondaryColor }};
            --card-accent: {{ $accentColor }};
            --card-bg: {{ $bgColor }};
            --card-text: {{ $textColor }};
        }
        body { font-family: '{{ $fontFamily }}', 'Cairo', sans-serif; }
        @@keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @@keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        @@keyframes slideInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @@keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            50% { box-shadow: 0 0 0 12px rgba(59, 130, 246, 0); }
        }
        @@keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .card-container { animation: fadeInUp 0.6s ease-out; }
        .profile-image { animation: fadeInScale 0.5s ease-out 0.2s both; }
        .info-section { animation: fadeInUp 0.5s ease-out 0.3s both; }
        .contact-btn { animation: fadeInUp 0.4s ease-out both; }
        .contact-btn:nth-child(1) { animation-delay: 0.4s; }
        .contact-btn:nth-child(2) { animation-delay: 0.5s; }
        .contact-btn:nth-child(3) { animation-delay: 0.6s; }
        .contact-btn:nth-child(4) { animation-delay: 0.7s; }
        .social-link { animation: slideInRight 0.4s ease-out both; }
        .social-link:nth-child(1) { animation-delay: 0.6s; }
        .social-link:nth-child(2) { animation-delay: 0.65s; }
        .social-link:nth-child(3) { animation-delay: 0.7s; }
        .social-link:nth-child(4) { animation-delay: 0.75s; }
        .social-link:nth-child(5) { animation-delay: 0.8s; }
        .save-contact-btn { animation: fadeInUp 0.5s ease-out 0.8s both; }
        .save-contact-btn:hover { animation: pulseGlow 1.5s ease-in-out infinite; }

        .cover-shimmer {
            background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.15) 50%, transparent 75%);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        .contact-btn {
            transition: all 0.3s ease;
        }
        .contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .social-link {
            transition: all 0.3s ease;
        }
        .social-link:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-8 px-4">
    <div class="w-full max-w-md card-container">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Cover Image -->
            <div class="h-36 relative" style="{{ $coverBg }}">
                @if($card->cover_image)
                    <img src="{{ asset('storage/' . $card->cover_image) }}" alt="{{ $card->full_name ?? $card->title }}" class="w-full h-full object-cover">
                @endif
                <div class="absolute inset-0 cover-shimmer pointer-events-none"></div>
            </div>

            <!-- Profile Section -->
            <div class="px-6 -mt-14 relative z-10">
                <div class="flex items-end gap-4">
                    @if($card->profile_image)
                        <img src="{{ asset('storage/' . $card->profile_image) }}" alt="{{ $card->full_name }}"
                             class="profile-image w-28 h-28 rounded-full border-4 border-white object-cover shadow-lg flex-shrink-0">
                    @else
                        <div class="profile-image w-28 h-28 rounded-full border-4 border-white flex items-center justify-center text-3xl font-bold shadow-lg flex-shrink-0" style="background: {{ $primaryColor }}15; color: var(--card-primary);">
                            {{ mb_substr($card->full_name ?? $card->title, 0, 1) }}
                        </div>
                    @endif
                    @if($card->logo)
                        <img src="{{ asset('storage/' . $card->logo) }}" alt="Logo" class="h-10 object-contain mb-2">
                    @endif
                </div>
            </div>

            <!-- Info Section -->
            <div class="px-6 pt-4 pb-6 info-section">
                <h1 class="text-2xl font-bold text-gray-900">{{ $card->full_name ?? $card->title }}</h1>
                @if($card->job_title)
                    <p class="font-semibold mt-1" style="color: var(--card-primary);">{{ $card->job_title }}</p>
                @endif
                @if($card->company)
                    <p class="text-gray-500 text-sm">{{ $card->company }}</p>
                @endif

                @if($card->bio)
                <p class="text-gray-600 text-sm leading-relaxed mt-4 border-r-4 pr-4" style="border-color: var(--card-primary);">
                    {{ $card->bio }}
                </p>
                @endif

                <!-- Contact Buttons -->
                <div class="grid grid-cols-2 gap-3 mt-6">
                    @if($card->phone)
                    <a href="tel:{{ $card->phone }}" class="contact-btn flex items-center justify-center gap-2 rounded-xl py-3 px-4 text-sm font-semibold" style="background: {{ $primaryColor }}15; color: var(--card-primary);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        اتصال
                    </a>
                    @endif

                    @if($card->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $card->whatsapp) }}" target="_blank" rel="noopener"
                       class="contact-btn flex items-center justify-center gap-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-xl py-3 px-4 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.623-1.467A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.115 0-4.116-.654-5.803-1.893l-.416-.311-2.743.87.889-2.663-.342-.433A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/></svg>
                        واتساب
                    </a>
                    @endif

                    @if($card->email)
                    <a href="mailto:{{ $card->email }}" class="contact-btn flex items-center justify-center gap-2 rounded-xl py-3 px-4 text-sm font-semibold" style="background: {{ $primaryColor }}15; color: var(--card-primary);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        بريد
                    </a>
                    @endif

                    @if($card->website)
                    <a href="{{ $card->website }}" target="_blank" rel="noopener"
                       class="contact-btn flex items-center justify-center gap-2 rounded-xl py-3 px-4 text-sm font-semibold" style="background: {{ $primaryColor }}15; color: var(--card-primary);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                        الموقع
                    </a>
                    @endif
                </div>

                @if($card->address)
                <div class="mt-4 bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-gray-500 text-xs mb-1">الموقع</p>
                    <p class="text-gray-800 text-sm font-medium">{{ $card->address }}</p>
                </div>
                @endif

                <!-- Contact Details -->
                <div class="mt-4 space-y-2">
                    @if($card->phone)
                    <div class="flex items-center gap-3 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span dir="ltr">{{ $card->phone }}</span>
                    </div>
                    @endif
                    @if($card->email)
                    <div class="flex items-center gap-3 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span dir="ltr">{{ $card->email }}</span>
                    </div>
                    @endif
                </div>

                <!-- Social Links -->
                @if($card->activeSocialLinks->count() > 0)
                <div class="mt-6">
                    <p class="text-gray-500 text-xs font-semibold mb-3">تابعني على</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($card->activeSocialLinks as $link)
                        <a href="{{ $link->url }}" target="_blank" rel="noopener"
                           class="social-link text-gray-600 px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2" style="background: {{ $primaryColor }}10;" onmouseover="this.style.background='{{ $primaryColor }}25'; this.style.color='var(--card-primary)';" onmouseout="this.style.background='{{ $primaryColor }}10'; this.style.color='#4B5563';">
                            @switch($link->platform)
                                @case('twitter')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    @break
                                @case('instagram')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    @break
                                @case('linkedin')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    @break
                                @case('snapchat')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/></svg>
                                    @break
                                @default
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            @endswitch
                            {{ ucfirst($link->platform) }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Save Contact -->
                <div class="mt-6">
                    <a href="{{ route('public.cards.download-vcard', $card->slug) }}"
                       class="save-contact-btn w-full text-white rounded-xl py-3.5 text-center font-bold block transition shadow-sm flex items-center justify-center gap-2" style="background: var(--card-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        حفظ جهة الاتصال
                    </a>
                </div>

                <!-- Share Button -->
                <div class="mt-3">
                    <button onclick="shareCard()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl py-3 text-center font-semibold transition flex items-center justify-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        مشاركة البطاقة
                    </button>
                </div>
            </div>
        </div>

        <!-- Powered By -->
        <p class="text-center text-gray-400 text-xs mt-6">
            بطاقة رقمية من <a href="{{ url('/') }}" class="hover:underline" style="color: var(--card-primary);">معروف</a>
        </p>
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
