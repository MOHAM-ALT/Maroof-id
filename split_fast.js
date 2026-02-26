const fs = require('fs');

const sourceFile = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/ai-workspace/templates/page/maroof-id-light.html';
const homeTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/public/home.blade.php';
const headerTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/components/public/header.blade.php';
const footerTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/components/public/footer.blade.php';
const cssTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/css/maroof-home.css';
const jsTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/public/js/maroof-home.js';
const layoutTarget = 'C:/Users/Moha4/OneDrive/Desktop/VS COOD/Datropix/maroof_id/resources/views/layouts/public.blade.php';

try {
    const htmlContent = fs.readFileSync(sourceFile, 'utf8');

    // 1. Extract CSS
    const styleStart = htmlContent.indexOf('<style>');
    const styleEnd = htmlContent.indexOf('</style>');
    let css = '';
    if (styleStart > -1 && styleEnd > -1) {
        css = htmlContent.substring(styleStart + 7, styleEnd).trim();
        fs.writeFileSync(cssTarget, css);
        console.log("CSS saved.");
    }

    // 2. Extract Body Inner
    const bodyStart = htmlContent.indexOf('<body>');
    const bodyEnd = htmlContent.indexOf('</body>');
    if (bodyStart === -1 || bodyEnd === -1) throw "No body found";
    let bodyInner = htmlContent.substring(bodyStart + 6, bodyEnd);

    // 3. Extract Script
    const scriptStart = bodyInner.lastIndexOf('<script>');
    const scriptEnd = bodyInner.lastIndexOf('</script>');
    if (scriptStart > -1 && scriptEnd > -1) {
        let js = bodyInner.substring(scriptStart + 8, scriptEnd).trim();
        fs.writeFileSync(jsTarget, js);
        console.log("JS saved.");

        // Remove script from bodyInner
        bodyInner = bodyInner.substring(0, scriptStart).trim();
    }

    // 4. Split HTML without Regex

    // Extract Header (.ann and .nav)
    const annStart = bodyInner.indexOf('<div class="ann">');
    const navEnd = bodyInner.indexOf('</nav>');
    if (annStart > -1 && navEnd > -1) {
        let headerHtml = bodyInner.substring(annStart, navEnd + 6);

        // Dynamic Routes
        headerHtml = headerHtml.replace(/href="#" class="ann-lnk"/g, `href="{{ route('templates.index') }}" class="ann-lnk"`);
        headerHtml = headerHtml.replace(/<a href="#" class="logo">/g, `<a href="{{ route('home') }}" class="logo">`);
        headerHtml = headerHtml.replace(/<a href="#" class="nl">المميزات<\\/a > /g, `<a href="{{ route('home') }}#features" class="nl">المميزات</a > `);
        headerHtml = headerHtml.replace(/<a href="#" class="nl">القوالب<\\/a>/g, `< a href = "{{ route('templates.index') }}" class= "nl" > القوالب</a > `);
        headerHtml = headerHtml.replace(/<a href="#" class="nl">الأسعار<\\/a>/g, `< a href = "{{ route('pricing') }}" class= "nl" > الأسعار</a > `);
        headerHtml = headerHtml.replace(/<a href="#" class="nl">الموزعون<\\/a>/g, `< a href = "{{ route('home') }}" class= "nl" > الموزعون</a > `);
        headerHtml = headerHtml.replace(/<a href="#" class="nl">اتصل بنا<\\/a>/g, `< a href = "{{ route('contact') }}" class= "nl" > اتصل بنا</a > `);
        headerHtml = headerHtml.replace(/<a href="#" class="btn-si">تسجيل الدخول<\\/a>/g, `< a href = "{{ route('login') }}" class= "btn-si" > تسجيل الدخول</a > `);
        headerHtml = headerHtml.replace(/<a href="#" class="btn-nav">/g, `< a href = "{{ route('register') }}" class= "btn-nav" > `);

        fs.writeFileSync(headerTarget, headerHtml);
        console.log("Header updated");
        
        // Remove header from bodyInner
        bodyInner = bodyInner.substring(navEnd + 6).trim();
    }

    // Extract Footer (<footer>)
    const footerStart = bodyInner.lastIndexOf('<footer>');
    const footerEnd = bodyInner.lastIndexOf('</footer>');
    if (footerStart > -1 && footerEnd > -1) {
        let footerHtml = bodyInner.substring(footerStart, footerEnd + 9);
        fs.writeFileSync(footerTarget, footerHtml.trim());
        console.log("Footer updated");
        
        // Remove footer from bodyInner
        bodyInner = bodyInner.substring(0, footerStart).trim();
    }

    // Replace Dashboard links in body
    bodyInner = bodyInner.replace(/href="#" class="hero-price"/g, `href = "{{ route('register') }}" class= "hero-price"`);
    bodyInner = bodyInner.replace(/href="#" class="hero-demo"/g, `href = "#how" class= "hero-demo"`);
    bodyInner = bodyInner.replace(/href="#" class="btn-gold"/g, `href = "{{ route('register') }}" class= "btn-gold"`);
    bodyInner = bodyInner.replace(/href="#" class="btn-ghost"/g, `href = "{{ route('templates.index') }}" class= "btn-ghost"`);
    bodyInner = bodyInner.replace(/href="#" class="plink"/g, `href = "{{ route('home') }}" class= "plink"`);

    // Remaining HTML is home content
    const homeBlade = `@extends('layouts.public')

@section('title', 'معروف - بطاقة التعريف الرقمية الذكية')
            @section('description', 'أنشئ بطاقة تعريفك الرقمية الذكية في دقائق. شارك معلوماتك بسهولة وأمان مع NFC و QR Code.')

            @push('head')
            < link rel = "stylesheet" href = "{{ asset('css/maroof-home.css') }}" >
            @endpush

            @push('scripts')
            < script src = "{{ asset('js/maroof-home.js') }}" defer ></script >
        @endpush

        @section('content')
${ bodyInner.trim() }
@endsection
                `;
    fs.writeFileSync(homeTarget, homeBlade);
    console.log("Home blade updated");
    
    // As maroof-home.css is pushed ONLY in home.blade.php, but it contains styles for header and footer which are shared,
    // we need to make sure layout.blade.php loads maroof-home.css globally OR we put header/footer css somewhere else.
    // For perfection and based on how beautiful it is, let's load maroof-home.css in public layout if not present.
    let layoutHtml = fs.readFileSync(layoutTarget, 'utf8');
    if(layoutHtml.indexOf('maroof-home.css') === -1) {
        layoutHtml = layoutHtml.replace('<!-- Additional Head Content -->', '<link rel="stylesheet" href="{{ asset(\'css/maroof-home.css\') }}">\n    <!-- Additional Head Content -->');
        fs.writeFileSync(layoutTarget, layoutHtml);
        console.log("Layout updated to include global CSS.");
    }
    
    process.exit(0);

} catch (e) {
    console.error("Error:", e);
    process.exit(1);
}
