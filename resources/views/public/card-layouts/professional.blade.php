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
            'https://schema.org' => null,
            'Person' => null,
        ];
        $jsonLd = [];
        $jsonLd['name'] = $card->full_name ?? $card->title;
        if ($card->job_title) $jsonLd['jobTitle'] = $card->job_title;
        if ($card->company) $jsonLd['worksFor'] = ['name' => $card->company];
        if ($card->email) $jsonLd['email'] = $card->email;
        if ($card->phone) $jsonLd['telephone'] = $card->phone;
        if ($card->website) $jsonLd['url'] = $card->website;
        if ($card->profile_image) $jsonLd['image'] = asset('storage/' . $card->profile_image);
        if ($card->bio) $jsonLd['description'] = $card->bio;
        $jsonLd['mainEntityOfPage'] = route('public.cards.show', $card->slug);
    @endphp
    {!! json_encode(array_merge(['@context' => 'https://schema.org', '@type' => 'Person'], $jsonLd), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    @php
        $designData = $card->design_data;
        if (is_string($designData)) $designData = json_decode($designData, true);
        $templateConfig = $card->template?->design_config ?? [];
        $primaryColor = $designData['primaryColor'] ?? $templateConfig['colors']['primary'] ?? '#6366F1';
    @endphp

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: {{ $primaryColor }};
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: #000000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Dot pattern overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
            z-index: 0;
        }

        /* Ambient glow */
        body::after {
            content: '';
            position: fixed;
            top: -40%;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, {{ $primaryColor }}15 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        @@keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @@keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.85);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @@keyframes shimmer {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }

        .card-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            position: relative;
        }

        /* Top highlight line */
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 10%;
            right: 10%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            z-index: 2;
        }

        .card-inner {
            padding: 40px 28px 32px;
        }

        /* Profile section */
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 28px;
        }

        .profile-image-wrap {
            position: relative;
            margin-bottom: 20px;
            animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }

        .profile-image-wrap img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.15);
            display: block;
        }

        .profile-image-wrap .ring-shadow {
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.05);
        }

        .profile-placeholder {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.05);
        }

        .profile-name {
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 6px;
        }

        .profile-title {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.45);
            font-weight: 400;
        }

        .profile-bio {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.50);
            line-height: 1.7;
            margin-top: 14px;
            max-width: 320px;
        }

        /* Logo */
        .card-logo {
            height: 36px;
            object-fit: contain;
            margin-bottom: 16px;
            opacity: 0.8;
        }

        /* Save contact button */
        .save-contact-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px 24px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 100px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Tajawal', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-bottom: 12px;
        }

        .save-contact-btn:hover {
            background: rgba(255, 255, 255, 0.14);
            transform: translateY(-1px);
        }

        .save-contact-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Call / WhatsApp split */
        .split-actions {
            display: flex;
            gap: 2px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.06);
            margin-bottom: 24px;
        }

        .split-actions a {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 12px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Tajawal', sans-serif;
            text-decoration: none;
            transition: background 0.3s ease;
            background: transparent;
        }

        .split-actions a:hover {
            background: rgba(255, 255, 255, 0.06);
        }

        .split-actions .divider {
            width: 1px;
            background: rgba(255, 255, 255, 0.08);
            align-self: stretch;
        }

        .split-actions svg {
            width: 18px;
            height: 18px;
        }

        .icon-green {
            color: #22C55E;
        }

        /* Info items list */
        .info-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 28px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px;
            border-radius: 14px;
            transition: background 0.2s ease;
            text-decoration: none;
        }

        .info-item:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .info-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon-box svg {
            width: 18px;
            height: 18px;
            color: rgba(255, 255, 255, 0.5);
        }

        .info-content {
            flex: 1;
            min-width: 0;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.35);
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 400;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Social links */
        .social-section {
            margin-bottom: 8px;
        }

        .social-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 14px;
            text-align: center;
        }

        .social-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .social-link {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-2px);
        }

        .social-link svg {
            width: 20px;
            height: 20px;
        }

        /* Brand colors */
        .social-twitter svg { color: #1DA1F2; }
        .social-instagram svg { color: #E4405F; }
        .social-linkedin svg { color: #0A66C2; }
        .social-snapchat svg { color: #FFFC00; }
        .social-tiktok svg { color: #ffffff; }
        .social-youtube svg { color: #FF0000; }
        .social-facebook svg { color: #1877F2; }
        .social-telegram svg { color: #26A5E4; }
        .social-github svg { color: #ffffff; }
        .social-default svg { color: rgba(255, 255, 255, 0.6); }

        /* Share button */
        .share-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            color: rgba(255, 255, 255, 0.4);
            font-size: 13px;
            font-weight: 500;
            font-family: 'Tajawal', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 16px;
        }

        .share-btn:hover {
            background: rgba(255, 255, 255, 0.04);
            color: rgba(255, 255, 255, 0.6);
        }

        .share-btn svg {
            width: 16px;
            height: 16px;
        }

        /* Footer */
        .card-footer {
            text-align: center;
            padding: 20px 0 0;
        }

        .card-footer a {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.2);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .card-footer a:hover {
            color: rgba(255, 255, 255, 0.4);
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 100px;
            padding: 12px 24px;
            color: #ffffff;
            font-size: 14px;
            font-family: 'Tajawal', sans-serif;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 999;
            pointer-events: none;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    </style>
</head>
<body>
    <div class="card-wrapper">
        <div class="glass-card">
            <div class="card-inner">

                <!-- Profile Section -->
                <div class="profile-section">

                    @if($card->logo)
                        <img src="{{ asset('storage/' . $card->logo) }}" alt="Logo" class="card-logo">
                    @endif

                    <div class="profile-image-wrap">
                        @if($card->profile_image)
                            <img src="{{ asset('storage/' . $card->profile_image) }}" alt="{{ $card->full_name ?? $card->title }}">
                            <div class="ring-shadow"></div>
                        @else
                            <div class="profile-placeholder">
                                {{ mb_substr($card->full_name ?? $card->title, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="profile-name">{{ $card->full_name ?? $card->title }}</div>

                    @if($card->job_title || $card->company)
                        <div class="profile-title">
                            {{ $card->job_title }}@if($card->job_title && $card->company) · @endif{{ $card->company }}
                        </div>
                    @endif

                    @if($card->bio)
                        <div class="profile-bio">{{ $card->bio }}</div>
                    @endif
                </div>

                <!-- Save Contact -->
                <a href="{{ route('public.cards.download-vcard', $card->slug) }}" class="save-contact-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    حفظ جهة الاتصال
                </a>

                <!-- Call / WhatsApp Split -->
                @if($card->phone || $card->whatsapp)
                <div class="split-actions">
                    @if($card->phone)
                    <a href="tel:{{ $card->phone }}">
                        <svg class="icon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        اتصال
                    </a>
                    @endif

                    @if($card->phone && $card->whatsapp)
                    <div class="divider"></div>
                    @endif

                    @if($card->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $card->whatsapp) }}" target="_blank" rel="noopener">
                        <svg class="icon-green" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.623-1.467A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.115 0-4.116-.654-5.803-1.893l-.416-.311-2.743.87.889-2.663-.342-.433A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/>
                        </svg>
                        واتساب
                    </a>
                    @endif
                </div>
                @endif

                <!-- Info Items -->
                <div class="info-list">
                    @if($card->phone)
                    <a href="tel:{{ $card->phone }}" class="info-item">
                        <div class="info-icon-box">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">الهاتف</div>
                            <div class="info-value" dir="ltr" style="text-align: right;">{{ $card->phone }}</div>
                        </div>
                    </a>
                    @endif

                    @if($card->email)
                    <a href="mailto:{{ $card->email }}" class="info-item">
                        <div class="info-icon-box">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">البريد</div>
                            <div class="info-value" dir="ltr" style="text-align: right;">{{ $card->email }}</div>
                        </div>
                    </a>
                    @endif

                    @if($card->website)
                    <a href="{{ $card->website }}" target="_blank" rel="noopener" class="info-item">
                        <div class="info-icon-box">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">الموقع</div>
                            <div class="info-value" dir="ltr" style="text-align: right;">{{ preg_replace('#^https?://#', '', $card->website) }}</div>
                        </div>
                    </a>
                    @endif

                    @if($card->address)
                    <div class="info-item">
                        <div class="info-icon-box">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">العنوان</div>
                            <div class="info-value">{{ $card->address }}</div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Social Links -->
                @if($card->activeSocialLinks->count() > 0)
                <div class="social-section">
                    <div class="social-section-title">تابعني على</div>
                    <div class="social-grid">
                        @foreach($card->activeSocialLinks as $link)
                        <a href="{{ $link->url }}" target="_blank" rel="noopener" class="social-link social-{{ $link->platform }}">
                            @switch($link->platform)
                                @case('twitter')
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    @break
                                @case('instagram')
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    @break
                                @case('linkedin')
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    @break
                                @case('snapchat')
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/></svg>
                                    @break
                                @case('tiktok')
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                    @break
                                @case('youtube')
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    @break
                                @case('facebook')
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    @break
                                @case('telegram')
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                                    @break
                                @case('github')
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                                    @break
                                @default
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                            @endswitch
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Share Button -->
                <button onclick="shareCard()" class="share-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                    </svg>
                    مشاركة البطاقة
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer">
            <a href="{{ url('/') }}">بطاقة رقمية من معروف</a>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">تم نسخ الرابط!</div>

    <script>
    function shareCard() {
        var shareData = {
            title: {!! json_encode($card->full_name ?? $card->title, JSON_UNESCAPED_UNICODE) !!},
            text: {!! json_encode(($card->job_title ?? '') . ($card->company ? ' - ' . $card->company : ''), JSON_UNESCAPED_UNICODE) !!},
            url: window.location.href
        };

        if (navigator.share) {
            navigator.share(shareData);
        } else {
            navigator.clipboard.writeText(window.location.href).then(function() {
                var toast = document.getElementById('toast');
                toast.classList.add('show');
                setTimeout(function() {
                    toast.classList.remove('show');
                }, 2500);
            });
        }
    }
    </script>
</body>
</html>
