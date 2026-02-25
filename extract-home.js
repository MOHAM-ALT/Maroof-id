const fs = require('fs');
const path = require('path');

const sourceFile = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/ai-workspace/templates/page/maroof-id-light.html';
const cssTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/css/maroof-home.css';
const jsTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/js/maroof-home.js';
const bladeTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/public/home.blade.php';

try {
    const htmlContent = fs.readFileSync(sourceFile, 'utf8');

    // Extract CSS
    const styleMatch = htmlContent.match(/<style>([\s\S]*?)<\/style>/i);
    if (styleMatch) {
        fs.writeFileSync(cssTarget, styleMatch[1].trim());
        console.log('CSS extracted.');
    } else {
        console.log('No <style> found.');
    }

    // Extract JS
    // The script is usually at the bottom before </body>
    const scriptMatch = htmlContent.match(/<script>([\s\S]*?)<\/script>/i);
    let jsContent = '';
    if (scriptMatch) {
        jsContent = scriptMatch[1].trim();
        fs.writeFileSync(jsTarget, jsContent);
        console.log('JS extracted.');
    } else {
        console.log('No <script> found.');
    }

    // Extract HTML inside <body>...</body> but without the <script> tags
    const bodyMatch = htmlContent.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
    if (bodyMatch) {
        let bodyInner = bodyMatch[1];

        // Remove script tags from body
        bodyInner = bodyInner.replace(/<script>[\s\S]*?<\/script>/ig, '');
        // Remove the nav because it might be already in layouts.public or we keep it? 
        // The user wants the FULL soul. The original maroof-id-light.html has its own <nav> inside the body.
        // Let's keep the whole body content, but wrap it in blade directives.

        // Let's manually replace dynamic data if needed, or simply dump the raw HTML and we can adapt it later.

        const bladeContent = `@extends('layouts.public')

@section('title', 'معروف - بطاقة التعريف الرقمية الذكية')
@section('description', 'أنشئ بطاقة تعريفك الرقمية الذكية في دقائق. شارك معلوماتك بسهولة وأمان مع NFC و QR Code.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/maroof-home.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/maroof-home.js') }}" defer></script>
@endpush

@section('content')
${bodyInner}
@endsection
`;
        fs.writeFileSync(bladeTarget, bladeContent);
        console.log('Blade updated with full HTML content.');
    } else {
        console.log('No <body> found.');
    }

} catch (e) {
    console.error('Error:', e);
}
