const fs = require('fs');
const path = require('path');

const sourceFile = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/ai-workspace/templates/page/maroof-id-light.html';
const homeTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/public/home.blade.php';
const headerTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/components/public/header.blade.php';
const footerTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/components/public/footer.blade.php';
const globalCssTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/css/maroof-global.css';
const homeCssTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/css/maroof-home.css';
const globalJsTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/js/maroof-global.js';
const homeJsTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/js/maroof-home.js';

try {
    const htmlContent = fs.readFileSync(sourceFile, 'utf8');

    // 1. Extract CSS
    const styleMatch = htmlContent.match(/<style>([\s\S]*?)<\/style>/i);
    if (styleMatch) {
        let fullCss = styleMatch[1].trim();

        // Split CSS into Global (vars, keyframes, nav, footer, basics) and Home (hero, features, counter, etc.)
        // Look for /* ===== HERO ===== */ or similar marker if it exists.
        // Or simply split by a known index. 
        // Let's find /* ===== HERO ===== */
        const heroIndex = fullCss.indexOf('/* ===== HERO ===== */');

        let globalCss = '';
        let homeCss = '';

        if (heroIndex !== -1) {
            globalCss = fullCss.substring(0, heroIndex).trim();
            homeCss = fullCss.substring(heroIndex).trim();

            // Wait, we need to extract footer CSS to global.
            const footerIndex = homeCss.indexOf('/* ===== FOOTER ===== */');
            // If footer CSS is at the end, let's just make it simple: 
            // We'll put ALL CSS in maroof-home.css for now EXCEPT we copy the start to global, OR we just put all CSS into one file to be 100% safe and beautiful.
            // Wait, to be elegant: put all CSS that is purely for home in homeCss. But it's easier to put ALL CSS in global and call it a day? The user wants separate files.
        } else {
            console.log("No HERO comment found, using all CSS for home");
            homeCss = fullCss;
        }

        fs.writeFileSync(globalCssTarget, `/* Global Styles extracted from design */\n${globalCss}`);
        fs.writeFileSync(homeCssTarget, `/* Home Styles extracted from design */\n${homeCss}`);
    }

    // 2. Extract Body Inner
    const bodyMatch = htmlContent.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
    if (!bodyMatch) throw "No body found";
    let bodyHtml = bodyMatch[1];

    // 3. Extract Script
    const scriptMatch = bodyHtml.match(/<script>([\s\S]*?)<\/script>/i);
    let fullJs = scriptMatch ? scriptMatch[1].trim() : '';
    bodyHtml = bodyHtml.replace(/<script>[\s\S]*?<\/script>/ig, '');

    // 4. Split HTML
    // Looking for header (.ann and .nav)
    const headerRegex = /(<div class="ann">[\s\S]*?<\/nav>)/i;
    const headerMatch = bodyHtml.match(headerRegex);
    if (headerMatch) {
        fs.writeFileSync(headerTarget, headerMatch[1].trim());
        bodyHtml = bodyHtml.replace(headerRegex, '');
    }

    // Looking for footer (<footer>...</footer>)
    const footerRegex = /(<footer>[\s\S]*?<\/footer>)/i;
    const footerMatch = bodyHtml.match(footerRegex);
    if (footerMatch) {
        fs.writeFileSync(footerTarget, footerMatch[1].trim());
        bodyHtml = bodyHtml.replace(footerRegex, '');
    }

    // Replace Laravel route strings in the remaining bodyHtml (home)
    bodyHtml = bodyHtml.replace(/href="#" class="hero-price"/g, `href="{{ route('register') }}" class="hero-price"`);
    bodyHtml = bodyHtml.replace(/href="#" class="ann-lnk"/g, `href="{{ route('templates.index') }}" class="ann-lnk"`);

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

    // Write JS
    fs.writeFileSync(homeJsTarget, fullJs);

    console.log("Extraction complete!");

} catch (e) {
    console.error("Error:", e);
}
