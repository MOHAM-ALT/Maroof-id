const fs = require('fs');
const path = require('path');

const sourceFile = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/ai-workspace/templates/page/maroof-id-light.html';
const homeTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/public/home.blade.php';
const headerTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/components/public/header.blade.php';
const footerTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/components/public/footer.blade.php';
const cssTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/css/maroof-home.css';
const jsTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/js/maroof-home.js';

try {
    const htmlContent = fs.readFileSync(sourceFile, 'utf8');

    // 1. Extract CSS
    const styleMatch = htmlContent.match(/<style>([\s\S]*?)<\/style>/i);
    if (styleMatch) {
        let fullCss = styleMatch[1].trim();
        fs.writeFileSync(cssTarget, fullCss);
        console.log("CSS saved to maroof-home.css");
    }

    // 2. Extract Body Inner
    const bodyMatch = htmlContent.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
    if (!bodyMatch) throw "No body found";
    let bodyHtml = bodyMatch[1];

    // 3. Extract Script
    const scriptMatch = bodyHtml.match(/<script>([\s\S]*?)<\/script>/i);
    if (scriptMatch) {
        fs.writeFileSync(jsTarget, scriptMatch[1].trim());
        console.log("JS saved to maroof-home.js");
    }
    bodyHtml = bodyHtml.replace(/<script>[\s\S]*?<\/script>/ig, '');

    // 4. Split HTML

    // Extract Header (.ann and .nav)
    const headerRegex = /(<div class="ann">[\s\S]*?<\/nav>)/i;
    const headerMatch = bodyHtml.match(headerRegex);
    if (headerMatch) {
        let headerHtml = headerMatch[1].trim();
        // Dynamic Routes
        headerHtml = headerHtml.replace(/href="#" class="ann-lnk"/g, `href="{{ route('templates.index') }}" class="ann-lnk"`);
        headerHtml = headerHtml.replace(/<a href="#" class="logo">/g, `<a href="{{ route('home') }}" class="logo">`);
        headerHtml = headerHtml.replace(/<a href="#" class="nl">المميزات<\/a>/g, `<a href="{{ route('home') }}#features" class="nl">المميزات</a>`);
        headerHtml = headerHtml.replace(/<a href="#" class="nl">القوالب<\/a>/g, `<a href="{{ route('templates.index') }}" class="nl">القوالب</a>`);
        headerHtml = headerHtml.replace(/<a href="#" class="nl">الأسعار<\/a>/g, `<a href="{{ route('pricing') }}" class="nl">الأسعار</a>`);
        headerHtml = headerHtml.replace(/<a href="#" class="nl">اتصل بنا<\/a>/g, `<a href="{{ route('contact') }}" class="nl">اتصل بنا</a>`);
        headerHtml = headerHtml.replace(/<a href="#" class="btn-si">تسجيل الدخول<\/a>/g, `<a href="{{ route('login') }}" class="btn-si">تسجيل الدخول</a>`);
        headerHtml = headerHtml.replace(/<a href="#" class="btn-nav">/g, `<a href="{{ route('register') }}" class="btn-nav">`);

        fs.writeFileSync(headerTarget, headerHtml);
        console.log("Header updated");
        bodyHtml = bodyHtml.replace(headerRegex, '');
    }

    // Extract Footer (<footer>)
    const footerRegex = /(<footer>[\s\S]*?<\/footer>)/i;
    const footerMatch = bodyHtml.match(footerRegex);
    if (footerMatch) {
        fs.writeFileSync(footerTarget, footerMatch[1].trim());
        console.log("Footer updated");
        bodyHtml = bodyHtml.replace(footerRegex, '');
    }

    // Replace Laravel route strings in the remaining bodyHtml (home)
    bodyHtml = bodyHtml.replace(/href="#" class="hero-price"/g, `href="{{ route('register') }}" class="hero-price"`);
    bodyHtml = bodyHtml.replace(/href="#" class="btn-gold"/g, `href="{{ route('register') }}" class="btn-gold"`);

    // Remaining HTML is home content
    const homeBlade = `@extends('layouts.public')

@section('title', 'معروف - بطاقة التعريف الرقمية الذكية')
@section('description', 'أنشئ بطاقة تعريفك الرقمية الذكية في دقائق. شارك معلوماتك بسهولة وأمان مع NFC و QR Code.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/maroof-home.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/maroof-home.js') }}" defer></script>
@endpush

@section('content')
${bodyHtml.trim()}
@endsection
`;
    fs.writeFileSync(homeTarget, homeBlade);
    console.log("Home blade updated");

} catch (e) {
    console.error("Error:", e);
}
