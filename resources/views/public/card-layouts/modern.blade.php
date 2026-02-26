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
        if (is_string($designData)) $designData = json_decode($designData, true);
        $templateConfig = $card->template?->design_config ?? [];
        $primaryColor = $designData['primaryColor'] ?? $templateConfig['colors']['primary'] ?? '#6366F1';
        $accentLight = $templateConfig['colors']['secondary'] ?? '#818CF8';
    @endphp

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: {{ $primaryColor }};
            --accent-light: {{ $accentLight }};
            --bg-body: #0B0E14;
            --bg-card: #12161F;
            --bg-surface: #181D2A;
            --text-primary: #FFFFFF;
            --text-secondary: rgba(255, 255, 255, 0.55);
            --text-dim: rgba(255, 255, 255, 0.38);
            --border-color: rgba(255, 255, 255, 0.06);
        }

        body {
            font-family: 'Tajawal', 'Space Grotesk', sans-serif;
            background-color: var(--bg-body);
            background-image:
                linear-gradient(rgba(99, 102, 241, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 24px 16px;
            color: var(--text-primary);
            direction: rtl;
        }

        /* Accent glow at top */
        body::before {
            content: '';
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 500px;
            background: radial-gradient(ellipse at center, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .card-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .card {
            background: var(--bg-card);
            border-radius: 24px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            position: relative;
        }

        /* Top accent bar */
        .accent-bar {
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent-light), var(--primary));
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
            position: relative;
        }

        @@keyframes shimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .card-content {
            padding: 28px 24px 24px;
        }

        /* Profile section: horizontal layout, photo RIGHT, text LEFT (RTL) */
        .profile-section {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 20px;
        }

        .profile-info {
            flex: 1;
            min-width: 0;
        }

        .profile-name {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.3;
            margin-bottom: 4px;
            font-family: 'Tajawal', sans-serif;
        }

        .profile-role {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            line-height: 1.4;
        }

        .profile-company {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        .profile-photo {
            width: 88px;
            height: 88px;
            border-radius: 20px;
            object-fit: cover;
            border: 2px solid var(--border-color);
            flex-shrink: 0;
        }

        .profile-photo-placeholder {
            width: 88px;
            height: 88px;
            border-radius: 20px;
            background: var(--bg-surface);
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
        }

        /* Bio */
        .bio-section {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .bio-text {
            font-size: 14px;
            line-height: 1.7;
            color: var(--text-dim);
        }

        /* Quick Actions: 3-column grid */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 24px;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 16px 8px;
            background: var(--bg-surface);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            text-decoration: none;
            color: var(--text-secondary);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .quick-action-btn:hover {
            background: rgba(99, 102, 241, 0.08);
            border-color: rgba(99, 102, 241, 0.2);
            color: var(--text-primary);
        }

        .quick-action-btn .icon-wrap {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quick-action-btn .icon-wrap svg {
            width: 22px;
            height: 22px;
        }

        .quick-action-btn .btn-label {
            font-size: 12px;
            font-weight: 500;
        }

        .action-save .icon-wrap { background: rgba(99, 102, 241, 0.12); color: var(--primary); }
        .action-call .icon-wrap { background: rgba(34, 197, 94, 0.12); color: #22C55E; }
        .action-whatsapp .icon-wrap { background: rgba(37, 211, 102, 0.12); color: #25D366; }

        /* Links section */
        .links-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 24px;
        }

        .links-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-dim);
            margin-bottom: 8px;
        }

        .link-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            background: var(--bg-surface);
            border-radius: 14px;
            border: 1px solid var(--border-color);
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .link-item:hover {
            background: rgba(99, 102, 241, 0.06);
            border-color: rgba(99, 102, 241, 0.15);
        }

        .link-icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .link-icon-box svg {
            width: 18px;
            height: 18px;
        }

        .link-content {
            flex: 1;
            min-width: 0;
        }

        .link-label {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 2px;
        }

        .link-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .link-chevron {
            color: var(--text-dim);
            flex-shrink: 0;
            transform: scaleX(-1); /* flip for RTL */
        }

        .link-chevron svg {
            width: 16px;
            height: 16px;
        }

        /* Social links */
        .social-section {
            margin-bottom: 24px;
        }

        .social-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .social-link {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .social-link:hover {
            transform: translateY(-2px);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .social-link svg {
            width: 20px;
            height: 20px;
        }

        /* Brand colors */
        .social-twitter svg { color: #1DA1F2; }
        .social-x svg { color: #FFFFFF; }
        .social-facebook svg { color: #1877F2; }
        .social-instagram svg { color: #E4405F; }
        .social-linkedin svg { color: #0A66C2; }
        .social-youtube svg { color: #FF0000; }
        .social-tiktok svg { color: #FFFFFF; }
        .social-snapchat svg { color: #FFFC00; }
        .social-telegram svg { color: #26A5E4; }
        .social-pinterest svg { color: #BD081C; }
        .social-github svg { color: #FFFFFF; }
        .social-dribbble svg { color: #EA4C89; }
        .social-behance svg { color: #1769FF; }
        .social-whatsapp svg { color: #25D366; }
        .social-default svg { color: var(--primary); }

        /* Footer */
        .card-footer {
            text-align: center;
            padding: 20px 24px 24px;
        }

        .share-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-dim);
            text-decoration: none;
            cursor: pointer;
            background: none;
            border: none;
            transition: color 0.2s ease;
            font-family: 'Space Grotesk', 'Tajawal', sans-serif;
        }

        .share-btn:hover {
            color: var(--primary);
        }

        .share-btn svg {
            width: 14px;
            height: 14px;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--bg-surface);
            color: var(--text-primary);
            padding: 12px 24px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            font-size: 14px;
            font-family: 'Tajawal', sans-serif;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 999;
            pointer-events: none;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        /* Entrance animation */
        @@keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            animation: fadeUp 0.5s ease forwards;
        }
    </style>
</head>
<body>

<div class="card-wrapper">
    <div class="card">
        <!-- Top accent bar with shimmer -->
        <div class="accent-bar"></div>

        <div class="card-content">
            <!-- Profile section: horizontal, photo on right (RTL) -->
            <div class="profile-section">
                <div class="profile-info">
                    <h1 class="profile-name">{{ $card->full_name ?? $card->title }}</h1>
                    @if($card->job_title)
                        <div class="profile-role">{{ $card->job_title }}</div>
                    @endif
                    @if($card->company)
                        <div class="profile-company">{{ $card->company }}</div>
                    @endif
                </div>
                @if($card->profile_image)
                    <img src="{{ asset('storage/' . $card->profile_image) }}" alt="{{ $card->full_name ?? $card->title }}" class="profile-photo">
                @else
                    <div class="profile-photo-placeholder">
                        {{ mb_substr($card->full_name ?? $card->title ?? '?', 0, 1) }}
                    </div>
                @endif
            </div>

            <!-- Bio -->
            @if($card->bio)
            <div class="bio-section">
                <p class="bio-text">{{ $card->bio }}</p>
            </div>
            @endif

            <!-- Quick Actions: 3 column grid -->
            <div class="quick-actions">
                {{-- Save Contact --}}
                <a href="{{ route('public.cards.download-vcard', $card->slug) }}" class="quick-action-btn action-save">
                    <div class="icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                    </div>
                    <span class="btn-label">حفظ</span>
                </a>

                {{-- Call --}}
                @if($card->phone)
                <a href="tel:{{ $card->phone }}" class="quick-action-btn action-call">
                    <div class="icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <span class="btn-label">اتصال</span>
                </a>
                @endif

                {{-- WhatsApp --}}
                @if($card->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $card->whatsapp) }}" target="_blank" rel="noopener" class="quick-action-btn action-whatsapp">
                    <div class="icon-wrap">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <span class="btn-label">واتساب</span>
                </a>
                @endif
            </div>

            <!-- Contact Links -->
            <div class="links-section">
                <div class="links-title">معلومات التواصل</div>

                @if($card->phone)
                <a href="tel:{{ $card->phone }}" class="link-item">
                    <div class="link-icon-box" style="background: rgba(34, 197, 94, 0.1); color: #22C55E;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <div class="link-content">
                        <div class="link-label">الهاتف</div>
                        <div class="link-value" dir="ltr">{{ $card->phone }}</div>
                    </div>
                    <div class="link-chevron">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </a>
                @endif

                @if($card->email)
                <a href="mailto:{{ $card->email }}" class="link-item">
                    <div class="link-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366F1;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <div class="link-content">
                        <div class="link-label">البريد الإلكتروني</div>
                        <div class="link-value" dir="ltr">{{ $card->email }}</div>
                    </div>
                    <div class="link-chevron">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </a>
                @endif

                @if($card->website)
                <a href="{{ $card->website }}" target="_blank" rel="noopener" class="link-item">
                    <div class="link-icon-box" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <div class="link-content">
                        <div class="link-label">الموقع الإلكتروني</div>
                        <div class="link-value" dir="ltr">{{ preg_replace('#^https?://#', '', $card->website) }}</div>
                    </div>
                    <div class="link-chevron">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </a>
                @endif

                @if($card->address)
                <a href="https://maps.google.com/?q={{ urlencode($card->address) }}" target="_blank" rel="noopener" class="link-item">
                    <div class="link-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="link-content">
                        <div class="link-label">العنوان</div>
                        <div class="link-value">{{ $card->address }}</div>
                    </div>
                    <div class="link-chevron">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </a>
                @endif
            </div>

            <!-- Social Links -->
            @if($card->activeSocialLinks && $card->activeSocialLinks->count() > 0)
            <div class="social-section">
                <div class="links-title" style="text-align: center; margin-bottom: 14px;">التواصل الاجتماعي</div>
                <div class="social-grid">
                    @foreach($card->activeSocialLinks as $socialLink)
                        @php $platform = strtolower($socialLink->platform); @endphp
                        <a href="{{ $socialLink->url }}" target="_blank" rel="noopener" class="social-link social-{{ $platform }}" title="{{ $socialLink->platform }}">
                            @switch($platform)
                                @case('twitter')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                    @break
                                @case('x')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    @break
                                @case('facebook')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    @break
                                @case('instagram')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678a6.162 6.162 0 100 12.324 6.162 6.162 0 100-12.324zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405a1.441 1.441 0 11-2.882 0 1.441 1.441 0 012.882 0z"/></svg>
                                    @break
                                @case('linkedin')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    @break
                                @case('youtube')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    @break
                                @case('tiktok')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                    @break
                                @case('snapchat')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.06c-.012.18-.022.345-.03.51.075.045.203.09.401.09.3-.016.659-.12.922-.214.04-.012.06-.012.09-.012.21 0 .39.12.42.298.03.18-.12.39-.334.45-.09.03-.21.06-.42.09-.63.12-1.05.27-1.17.57-.06.18-.06.33-.09.51-.02.11-.06.239-.18.36-.12.15-.36.24-.63.24-.09 0-.21-.016-.33-.045-1.02-.27-2.04-.39-3-.39-1.62 0-3.09.72-4.32 2.1-.42.48-.72.93-.93 1.23-.21.33-.39.54-.63.54-.24 0-.42-.21-.63-.54-.21-.3-.51-.75-.93-1.23-1.23-1.38-2.7-2.1-4.32-2.1-.96 0-1.98.12-3 .39-.12.029-.24.045-.33.045-.27 0-.51-.09-.63-.24-.12-.121-.16-.25-.18-.36-.03-.18-.03-.33-.09-.51-.12-.3-.54-.45-1.17-.57-.21-.03-.33-.06-.42-.09-.21-.06-.36-.27-.33-.45.03-.18.21-.298.42-.298.03 0 .06 0 .09.012.27.09.63.21.92.214.2 0 .33-.045.41-.09-.01-.165-.02-.33-.03-.51l-.003-.06c-.105-1.628-.23-3.654.3-4.847C4.447 1.069 7.804.793 8.794.793h3.412z"/></svg>
                                    @break
                                @case('telegram')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                                    @break
                                @case('pinterest')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 0 1 .083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641 0 12.017 0z"/></svg>
                                    @break
                                @case('github')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                                    @break
                                @case('dribbble')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 24C5.385 24 0 18.615 0 12S5.385 0 12 0s12 5.385 12 12-5.385 12-12 12zm10.12-10.358c-.35-.11-3.17-.953-6.384-.438 1.34 3.684 1.887 6.684 1.992 7.308a10.29 10.29 0 004.395-6.87zm-6.115 7.808c-.153-.9-.75-4.032-2.19-7.77l-.066.02c-5.79 2.015-7.86 6.025-8.04 6.4a10.161 10.161 0 006.29 2.166c1.42 0 2.77-.29 4-.816zm-11.62-2.58c.232-.4 3.045-5.055 8.332-6.765.135-.045.27-.084.405-.12-.26-.585-.54-1.167-.832-1.74C7.17 11.775 2.206 11.71 1.756 11.7l-.004.312c0 2.633.998 5.037 2.634 6.855zm-2.42-8.955c.46.008 4.683.026 9.477-1.248-1.698-3.018-3.53-5.558-3.8-5.928-2.868 1.35-5.01 3.99-5.676 7.17zm7.56-7.872c.282.39 2.145 2.906 3.822 6 3.645-1.365 5.19-3.44 5.373-3.702A10.166 10.166 0 0012.002 1.8a10.3 10.3 0 00-2.48.303zm10.2 3.49c-.214.292-1.89 2.478-5.67 4.023.242.487.47.98.686 1.478.075.17.15.34.22.51 3.4-.43 6.78.26 7.11.33-.02-2.42-.88-4.64-2.345-6.34z"/></svg>
                                    @break
                                @case('behance')
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.938 4.503c.702 0 1.34.06 1.92.188.577.13 1.07.33 1.485.61.41.28.733.65.96 1.12.225.47.34 1.05.34 1.73 0 .74-.17 1.36-.507 1.86-.338.5-.837.9-1.502 1.22.906.26 1.576.72 2.022 1.37.448.66.665 1.45.665 2.36 0 .75-.13 1.39-.41 1.93-.28.55-.67 1-1.16 1.35-.48.348-1.05.6-1.67.767-.63.165-1.27.25-1.95.25H0V4.51h6.938v-.007zM6.545 10.16c.6 0 1.09-.16 1.47-.48.38-.32.57-.77.57-1.37 0-.36-.06-.66-.18-.89-.12-.23-.28-.42-.49-.56a2.07 2.07 0 00-.74-.3 3.823 3.823 0 00-.91-.1H3.486v3.7h3.06zm.135 5.98c.34 0 .66-.04.97-.12.31-.08.58-.21.81-.39.23-.18.41-.42.55-.71.13-.29.2-.65.2-1.09 0-.85-.23-1.47-.69-1.85-.46-.38-1.08-.57-1.86-.57H3.49v4.73h3.19zM15.948 4.178h6.324v1.72h-6.324v-1.72zM22.68 11.583c-.24-.67-.58-1.24-1.02-1.72-.44-.48-.97-.853-1.59-1.12-.62-.267-1.3-.4-2.03-.4s-1.41.133-2.03.4-.16.64-1.59 1.12-.78 1.05-1.02 1.72c-.24.67-.36 1.41-.36 2.2s.12 1.53.36 2.2c.24.67.58 1.24 1.02 1.72.44.48.97.855 1.59 1.12.62.27 1.3.4 2.03.4.93 0 1.71-.19 2.34-.58.63-.39 1.14-.93 1.53-1.61l-1.94-1.01c-.17.43-.44.77-.81 1.02-.37.24-.81.36-1.31.36-.57 0-1.07-.15-1.48-.45-.41-.3-.67-.77-.78-1.41h6.78c.02-.2.03-.4.03-.61 0-.79-.12-1.53-.36-2.2zm-6.78 1.22c.06-.57.26-1.06.62-1.47.36-.4.84-.6 1.44-.6.65 0 1.13.19 1.44.57.3.38.49.88.56 1.5h-4.06z"/></svg>
                                    @break
                                @default
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="social-default"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            @endswitch
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="card-footer">
            <button class="share-btn" onclick="shareCard()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="18" cy="5" r="3"/>
                    <circle cx="6" cy="12" r="3"/>
                    <circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
                مشاركة البطاقة
            </button>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast" id="toast"></div>

<script>
    function showToast(message) {
        var toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(function() {
            toast.classList.remove('show');
        }, 2500);
    }

    function shareCard() {
        var shareData = {
            title: {!! json_encode($card->full_name ?? $card->title, JSON_UNESCAPED_UNICODE) !!},
            text: {!! json_encode(($card->job_title ? $card->job_title . ($card->company ? ' - ' . $card->company : '') : ($card->bio ?? 'بطاقة رقمية احترافية')), JSON_UNESCAPED_UNICODE) !!},
            url: {!! json_encode(route('public.cards.show', $card->slug)) !!}
        };

        if (navigator.share) {
            navigator.share(shareData).catch(function() {});
        } else {
            // Clipboard fallback
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(shareData.url).then(function() {
                    showToast('تم نسخ الرابط');
                }).catch(function() {
                    fallbackCopy(shareData.url);
                });
            } else {
                fallbackCopy(shareData.url);
            }
        }
    }

    function fallbackCopy(text) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        try {
            document.execCommand('copy');
            showToast('تم نسخ الرابط');
        } catch (e) {
            showToast('لم يتم النسخ');
        }
        document.body.removeChild(textarea);
    }
</script>
</body>
</html>
