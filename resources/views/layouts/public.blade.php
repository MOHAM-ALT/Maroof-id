<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'معروف - بطاقة التعريف الرقمية الذكية')</title>
    <meta name="description" content="@yield('description', 'معروف - منصة سعودية لإنشاء بطاقات التعريف الرقمية الذكية. شارك معلوماتك بسهولة وأمان مع NFC وQR Code.')">
    <meta name="keywords" content="بطاقة تعريف رقمية, NFC, QR Code, بطاقة ذكية, معروف, السعودية">
    <meta name="author" content="Maroof.id">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'معروف - بطاقة التعريف الرقمية الذكية')">
    <meta property="og:description" content="@yield('og_description', 'منصة سعودية لإنشاء بطاقات التعريف الرقمية الذكية')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'معروف - بطاقة التعريف الرقمية الذكية')">
    <meta name="twitter:description" content="@yield('twitter_description', 'منصة سعودية لإنشاء بطاقات التعريف الرقمية الذكية')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Google Fonts - Cairo, Tajawal, Noto Naskh Arabic, DM Serif Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Tajawal:wght@400;500;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Head Content -->
    @stack('head')
</head>
<body class="antialiased bg-gray-50 text-gray-900" dir="rtl">

    <!-- Header -->
    @include('components.public.header')

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.public.footer')

    <!-- Additional Scripts -->
    @stack('scripts')

</body>
</html>
