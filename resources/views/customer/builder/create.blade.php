<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $card ? 'تعديل البطاقة' : 'استوديو التصميم' }} - معروف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                        tajawal: ['Tajawal', 'sans-serif'],
                        almarai: ['Almarai', 'sans-serif'],
                        ibm: ['IBM Plex Sans Arabic', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;800&family=Almarai:wght@300;400;700;800&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Cairo', sans-serif; margin: 0; overflow: hidden; }
        .panel { height: calc(100vh - 52px); overflow-y: auto; scrollbar-width: thin; }
        .panel::-webkit-scrollbar { width: 4px; }
        .panel::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .card-canvas {
            height: calc(100vh - 52px);
            background: #f3f4f6;
            background-image: radial-gradient(circle, #d1d5db 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .tab-btn.active { color: #2563eb; border-bottom: 2px solid #2563eb; background: #eff6ff; }
        .color-dot { transition: all 0.15s; cursor: pointer; }
        .color-dot:hover { transform: scale(1.25); }
        .color-dot.selected { ring: 2px; box-shadow: 0 0 0 3px #2563eb; }
        .field-group label { display: block; font-size: 11px; font-weight: 600; color: #6b7280; margin-bottom: 4px; }
        .field-group input, .field-group textarea, .field-group select {
            width: 100%; border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 12px;
            font-size: 13px; outline: none; transition: border 0.2s; font-family: 'Cairo', sans-serif;
        }
        .field-group input:focus, .field-group textarea:focus, .field-group select:focus {
            border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .upload-zone {
            border: 2px dashed #d1d5db; border-radius: 12px; padding: 16px;
            text-align: center; cursor: pointer; transition: all 0.3s;
        }
        .upload-zone:hover { border-color: #2563eb; background: #eff6ff; }
        .upload-zone.has-image { border-style: solid; border-color: #10b981; }
        .preview-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .save-indicator { transition: all 0.3s; }
        .toast {
            position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
            z-index: 200; padding: 12px 24px; border-radius: 12px;
            font-size: 14px; font-weight: 600; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp { from { opacity: 0; transform: translate(-50%, 20px); } to { opacity: 1; transform: translate(-50%, 0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .section-title { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
        .style-btn {
            border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px; text-align: center;
            font-size: 11px; font-weight: 500; cursor: pointer; transition: all 0.2s;
        }
        .style-btn:hover { border-color: #2563eb; background: #eff6ff; }
        .style-btn.active { border-color: #2563eb; background: #dbeafe; color: #1d4ed8; }
        .qr-container canvas { border-radius: 8px; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- ═══════════════ TOP TOOLBAR ═══════════════ -->
    <div class="bg-white border-b h-[52px] flex items-center justify-between px-4 sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.cards.index') }}" class="text-gray-400 hover:text-gray-700 transition p-1.5 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="h-5 w-px bg-gray-200"></div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                </div>
                <span class="text-sm font-bold text-gray-800">{{ $card ? 'تعديل: ' . Str::limit($card->title, 20) : 'استوديو التصميم' }}</span>
            </div>

            <!-- Auto-save indicator -->
            <div id="save-status" class="save-indicator flex items-center gap-1.5 text-xs text-gray-400 mr-4">
                <div id="save-dot" class="w-2 h-2 rounded-full bg-gray-300"></div>
                <span id="save-text">جاهز</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Preview Modes -->
            <div class="hidden md:flex bg-gray-100 rounded-lg p-0.5">
                <button onclick="setPreview('mobile')" id="btn-mobile" class="px-2.5 py-1 rounded-md text-xs font-medium bg-white shadow text-blue-600 transition" title="موبايل">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </button>
                <button onclick="setPreview('tablet')" id="btn-tablet" class="px-2.5 py-1 rounded-md text-xs font-medium text-gray-500 transition" title="تابلت">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </button>
                <button onclick="setPreview('desktop')" id="btn-desktop" class="px-2.5 py-1 rounded-md text-xs font-medium text-gray-500 transition" title="سطح مكتب">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </button>
            </div>

            <div class="h-5 w-px bg-gray-200"></div>

            <!-- Undo/Redo -->
            <button onclick="undo()" id="btn-undo" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 disabled:opacity-30 transition" title="تراجع (Ctrl+Z)" disabled>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </button>
            <button onclick="redo()" id="btn-redo" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 disabled:opacity-30 transition" title="إعادة (Ctrl+Y)" disabled>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/></svg>
            </button>

            <div class="h-5 w-px bg-gray-200"></div>

            <!-- Preview Public -->
            <button onclick="previewPublic()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 transition" title="معاينة البطاقة العامة">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>

            <!-- Save Buttons -->
            <button onclick="saveCard(false)" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition">
                حفظ مسودة
            </button>
            <button onclick="saveCard(true)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                حفظ ونشر
            </button>
        </div>
    </div>

    <!-- ═══════════════ MAIN LAYOUT ═══════════════ -->
    <div class="flex">
        <!-- ═══════════════ LEFT PANEL ═══════════════ -->
        <div class="w-[340px] bg-white border-l flex flex-col" style="height: calc(100vh - 52px);">
            <!-- Panel Tabs -->
            <div class="flex border-b flex-shrink-0">
                <button onclick="switchTab('info')" class="tab-btn active flex-1 py-2.5 text-xs font-semibold transition" data-tab="info">
                    <svg class="w-4 h-4 mx-auto mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    المعلومات
                </button>
                <button onclick="switchTab('style')" class="tab-btn flex-1 py-2.5 text-xs font-semibold text-gray-500 transition" data-tab="style">
                    <svg class="w-4 h-4 mx-auto mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    التصميم
                </button>
                <button onclick="switchTab('social')" class="tab-btn flex-1 py-2.5 text-xs font-semibold text-gray-500 transition" data-tab="social">
                    <svg class="w-4 h-4 mx-auto mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    التواصل
                </button>
                <button onclick="switchTab('settings')" class="tab-btn flex-1 py-2.5 text-xs font-semibold text-gray-500 transition" data-tab="settings">
                    <svg class="w-4 h-4 mx-auto mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    الإعدادات
                </button>
            </div>

            <!-- Panel Content -->
            <div class="flex-1 overflow-y-auto" style="scrollbar-width: thin;">
                <!-- ══ INFO TAB ══ -->
                <div id="panel-info" class="p-4 space-y-3">
                    <!-- Images Section -->
                    <div class="section-title">الصور</div>
                    <div class="grid grid-cols-3 gap-2">
                        <!-- Profile -->
                        <div>
                            <div class="upload-zone p-2" id="zone-profile" onclick="document.getElementById('upload-profile').click()">
                                <div id="thumb-profile" class="w-14 h-14 rounded-full mx-auto bg-gray-100 overflow-hidden flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">صورة شخصية</p>
                                <input type="file" id="upload-profile" accept="image/*" class="hidden" onchange="uploadImage(this,'profile')">
                            </div>
                        </div>
                        <!-- Cover -->
                        <div>
                            <div class="upload-zone p-2" id="zone-cover" onclick="document.getElementById('upload-cover').click()">
                                <div id="thumb-cover" class="w-full h-14 rounded bg-gray-100 overflow-hidden flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">غلاف</p>
                                <input type="file" id="upload-cover" accept="image/*" class="hidden" onchange="uploadImage(this,'cover')">
                            </div>
                        </div>
                        <!-- Logo -->
                        <div>
                            <div class="upload-zone p-2" id="zone-logo" onclick="document.getElementById('upload-logo').click()">
                                <div id="thumb-logo" class="w-14 h-14 rounded mx-auto bg-gray-100 overflow-hidden flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">شعار</p>
                                <input type="file" id="upload-logo" accept="image/*" class="hidden" onchange="uploadImage(this,'logo')">
                            </div>
                        </div>
                    </div>

                    <div class="section-title mt-4">المعلومات الأساسية</div>
                    <div class="field-group">
                        <label>الاسم الكامل *</label>
                        <input type="text" id="f-name" placeholder="أحمد محمد العلي" oninput="updatePreview()">
                    </div>
                    <div class="field-group">
                        <label>عنوان البطاقة</label>
                        <input type="text" id="f-title" placeholder="بطاقتي الاحترافية" oninput="updatePreview()">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="field-group">
                            <label>المسمى الوظيفي</label>
                            <input type="text" id="f-job" placeholder="مدير التسويق" oninput="updatePreview()">
                        </div>
                        <div class="field-group">
                            <label>الشركة</label>
                            <input type="text" id="f-company" placeholder="شركة معروف" oninput="updatePreview()">
                        </div>
                    </div>
                    <div class="field-group">
                        <label>نبذة تعريفية</label>
                        <textarea id="f-bio" rows="2" placeholder="خبير في التسويق الرقمي بخبرة 10+ سنوات..." oninput="updatePreview()"></textarea>
                    </div>

                    <div class="section-title mt-4">معلومات التواصل</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="field-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" id="f-email" placeholder="ahmed@example.com" dir="ltr" class="text-left" oninput="updatePreview()">
                        </div>
                        <div class="field-group">
                            <label>رقم الهاتف</label>
                            <input type="tel" id="f-phone" placeholder="+966 5X XXX XXXX" dir="ltr" class="text-left" oninput="updatePreview()">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="field-group">
                            <label>واتساب</label>
                            <input type="tel" id="f-whatsapp" placeholder="+966 5X XXX XXXX" dir="ltr" class="text-left" oninput="updatePreview()">
                        </div>
                        <div class="field-group">
                            <label>الموقع الإلكتروني</label>
                            <input type="url" id="f-website" placeholder="https://example.com" dir="ltr" class="text-left" oninput="updatePreview()">
                        </div>
                    </div>
                    <div class="field-group">
                        <label>العنوان</label>
                        <input type="text" id="f-address" placeholder="الرياض، المملكة العربية السعودية" oninput="updatePreview()">
                    </div>
                </div>

                <!-- ══ STYLE TAB ══ -->
                <div id="panel-style" class="p-4 space-y-4 hidden">
                    <!-- Template Selection -->
                    <div>
                        <div class="section-title">القالب</div>
                        <div class="grid grid-cols-3 gap-2 max-h-[200px] overflow-y-auto pr-1">
                            @foreach($templates as $template)
                            @php
                                $cfg = $template->design_config ?? [];
                                $tPri = $cfg['colors']['primary'] ?? '#2563EB';
                                $tSec = $cfg['colors']['secondary'] ?? '#3B82F6';
                            @endphp
                            <div onclick="selectTemplate({{ $template->id }})"
                                 class="style-btn cursor-pointer template-opt" data-id="{{ $template->id }}"
                                 data-config="{{ json_encode($cfg) }}"
                                 data-has-html="{{ $template->html_content ? '1' : '0' }}">
                                <div class="w-full h-10 rounded-md" style="background: linear-gradient(135deg, {{ $tPri }}, {{ $tSec }})"></div>
                                <p class="text-[10px] mt-1 truncate">{{ $template->name }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Primary Color -->
                    <div>
                        <div class="section-title">اللون الرئيسي</div>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['#2563EB','#7C3AED','#059669','#DC2626','#D97706','#0891B2','#4F46E5','#BE185D','#1F2937','#0EA5E9','#6366F1','#EC4899'] as $color)
                            <div onclick="setPrimaryColor('{{ $color }}')" class="color-dot w-7 h-7 rounded-full border-2 border-white shadow-sm" style="background:{{ $color }}"></div>
                            @endforeach
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <input type="color" id="custom-color" value="#2563EB" onchange="setPrimaryColor(this.value)" class="w-7 h-7 rounded cursor-pointer border-0">
                            <input type="text" id="color-hex" value="#2563EB" class="w-20 border rounded-lg px-2 py-1 text-xs text-center font-mono" dir="ltr" onchange="setPrimaryColor(this.value)">
                        </div>
                    </div>

                    <!-- Background Style -->
                    <div>
                        <div class="section-title">خلفية الغلاف</div>
                        <div class="grid grid-cols-4 gap-1.5">
                            <button onclick="setBgStyle('gradient')" class="style-btn active text-[10px]" id="bg-gradient">تدرج</button>
                            <button onclick="setBgStyle('solid')" class="style-btn text-[10px]" id="bg-solid">موحد</button>
                            <button onclick="setBgStyle('pattern')" class="style-btn text-[10px]" id="bg-pattern">نمط</button>
                            <button onclick="setBgStyle('dark')" class="style-btn text-[10px]" id="bg-dark">داكن</button>
                        </div>
                    </div>

                    <!-- Card Layout -->
                    <div>
                        <div class="section-title">تخطيط البطاقة</div>
                        <div class="grid grid-cols-3 gap-1.5">
                            <button onclick="setLayout('centered')" class="style-btn active text-[10px]" id="layout-centered">وسطي</button>
                            <button onclick="setLayout('right')" class="style-btn text-[10px]" id="layout-right">يمين</button>
                            <button onclick="setLayout('modern')" class="style-btn text-[10px]" id="layout-modern">عصري</button>
                        </div>
                    </div>

                    <!-- Font Selection -->
                    <div>
                        <div class="section-title">الخط</div>
                        <div class="grid grid-cols-2 gap-1.5">
                            <button onclick="setFont('Cairo')" class="style-btn active text-[10px] font-cairo" id="font-Cairo">Cairo</button>
                            <button onclick="setFont('Tajawal')" class="style-btn text-[10px] font-tajawal" id="font-Tajawal">Tajawal</button>
                            <button onclick="setFont('Almarai')" class="style-btn text-[10px] font-almarai" id="font-Almarai">Almarai</button>
                            <button onclick="setFont('IBM Plex Sans Arabic')" class="style-btn text-[10px] font-ibm" id="font-IBM Plex Sans Arabic">IBM Plex</button>
                        </div>
                    </div>

                    <!-- Border Radius -->
                    <div>
                        <div class="section-title">حواف البطاقة</div>
                        <input type="range" id="range-radius" min="0" max="32" value="16" oninput="setBorderRadius(this.value)" class="w-full accent-blue-600">
                        <div class="flex justify-between text-[10px] text-gray-400"><span>حادة</span><span>{{ '16px' }}</span><span>دائرية</span></div>
                    </div>

                    <!-- Shadow -->
                    <div>
                        <div class="section-title">الظل</div>
                        <div class="grid grid-cols-4 gap-1.5">
                            <button onclick="setShadow('none')" class="style-btn text-[10px]" id="shadow-none">بدون</button>
                            <button onclick="setShadow('sm')" class="style-btn text-[10px]" id="shadow-sm">خفيف</button>
                            <button onclick="setShadow('lg')" class="style-btn active text-[10px]" id="shadow-lg">متوسط</button>
                            <button onclick="setShadow('2xl')" class="style-btn text-[10px]" id="shadow-2xl">قوي</button>
                        </div>
                    </div>

                    <!-- Contact Button Style -->
                    <div>
                        <div class="section-title">أزرار التواصل</div>
                        <div class="grid grid-cols-3 gap-1.5">
                            <button onclick="setBtnStyle('outline')" class="style-btn active text-[10px]" id="btnstyle-outline">حدود</button>
                            <button onclick="setBtnStyle('filled')" class="style-btn text-[10px]" id="btnstyle-filled">ملون</button>
                            <button onclick="setBtnStyle('soft')" class="style-btn text-[10px]" id="btnstyle-soft">ناعم</button>
                        </div>
                    </div>
                </div>

                <!-- ══ SOCIAL TAB ══ -->
                <div id="panel-social" class="p-4 space-y-3 hidden">
                    <div class="section-title">روابط التواصل الاجتماعي</div>
                    <p class="text-[11px] text-gray-400 -mt-1">أضف روابطك وستظهر كأيقونات في البطاقة</p>

                    @php
                    $socials = [
                        'twitter' => ['X (تويتر)', 'https://x.com/username'],
                        'instagram' => ['إنستغرام', 'https://instagram.com/username'],
                        'linkedin' => ['لينكدإن', 'https://linkedin.com/in/username'],
                        'snapchat' => ['سناب شات', 'https://snapchat.com/add/username'],
                        'tiktok' => ['تيك توك', 'https://tiktok.com/@username'],
                        'youtube' => ['يوتيوب', 'https://youtube.com/@channel'],
                        'facebook' => ['فيسبوك', 'https://facebook.com/username'],
                        'telegram' => ['تيليجرام', 'https://t.me/username'],
                        'github' => ['GitHub', 'https://github.com/username'],
                        'behance' => ['Behance', 'https://behance.net/username'],
                        'dribbble' => ['Dribbble', 'https://dribbble.com/username'],
                    ];
                    @endphp

                    @foreach($socials as $platform => [$label, $placeholder])
                    <div class="field-group">
                        <label>{{ $label }}</label>
                        <input type="url" id="social-{{ $platform }}" placeholder="{{ $placeholder }}" dir="ltr" class="text-left text-xs" oninput="updatePreview()">
                    </div>
                    @endforeach
                </div>

                <!-- ══ SETTINGS TAB ══ -->
                <div id="panel-settings" class="p-4 space-y-4 hidden">
                    <!-- Slug / URL -->
                    <div>
                        <div class="section-title">رابط البطاقة</div>
                        <div class="flex items-center border rounded-lg overflow-hidden">
                            <span class="bg-gray-50 px-2 py-2 text-xs text-gray-400 border-l whitespace-nowrap" dir="ltr">{{ url('/card/') }}/</span>
                            <input type="text" id="f-slug" class="flex-1 px-2 py-2 text-xs outline-none" dir="ltr" placeholder="ahmed-mohammed">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">سيتم توليده تلقائياً من الاسم إذا تُرك فارغاً</p>
                    </div>

                    <!-- Visibility -->
                    <div>
                        <div class="section-title">الظهور</div>
                        <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-gray-50">
                            <input type="checkbox" id="f-public" class="w-4 h-4 rounded text-blue-600">
                            <div>
                                <p class="text-xs font-semibold">نشر البطاقة</p>
                                <p class="text-[10px] text-gray-400">ستكون مرئية للجميع عبر الرابط</p>
                            </div>
                        </label>
                    </div>

                    <!-- Password Protection -->
                    <div>
                        <div class="section-title">حماية البطاقة</div>
                        <div class="field-group">
                            <label>كلمة مرور (اختياري)</label>
                            <input type="text" id="f-password" placeholder="اتركه فارغاً بدون حماية">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">سيُطلب من الزائر إدخال كلمة المرور</p>
                    </div>

                    <!-- Expiry -->
                    <div>
                        <div class="section-title">تاريخ الانتهاء</div>
                        <div class="field-group">
                            <label>ينتهي في (اختياري)</label>
                            <input type="datetime-local" id="f-expires">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">بعد هذا التاريخ لن تكون البطاقة متاحة</p>
                    </div>

                    <!-- QR Code -->
                    <div>
                        <div class="section-title">رمز QR</div>
                        <div id="qr-container" class="bg-gray-50 rounded-xl p-4 text-center">
                            <div id="qr-code" class="inline-block bg-white p-3 rounded-lg shadow-sm"></div>
                            <p class="text-[10px] text-gray-400 mt-2">سيتم تحديثه تلقائياً بعد الحفظ</p>
                            <button onclick="downloadQR()" class="mt-2 text-xs text-blue-600 hover:underline font-medium" id="btn-download-qr" style="display:none">تحميل QR</button>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div>
                        <div class="section-title">SEO</div>
                        <div class="field-group">
                            <label>عنوان ميتا (Meta Title)</label>
                            <input type="text" id="f-meta-title" placeholder="سيتم توليده من الاسم تلقائياً">
                        </div>
                        <div class="field-group mt-2">
                            <label>وصف ميتا (Meta Description)</label>
                            <textarea id="f-meta-desc" rows="2" placeholder="سيتم توليده من النبذة تلقائياً" class="text-xs"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════ CENTER: LIVE PREVIEW ═══════════════ -->
        <div class="flex-1 card-canvas flex items-start justify-center py-8 px-4 overflow-auto">
            <div id="card-preview" class="preview-card bg-white w-[380px] min-h-[500px] rounded-2xl overflow-hidden relative" style="box-shadow: 0 10px 25px rgba(0,0,0,0.15); border-radius: 16px;">
                <!-- Cover -->
                <div id="p-cover" class="h-40 relative overflow-hidden" style="background: linear-gradient(135deg, #2563EB, #7C3AED);">
                    <img id="p-cover-img" src="" class="w-full h-full object-cover hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/10"></div>
                </div>

                <!-- Profile Section -->
                <div id="p-profile-section" class="relative px-6 -mt-16 text-center">
                    <div id="p-avatar" class="w-28 h-28 rounded-full border-4 border-white bg-gray-200 mx-auto overflow-hidden shadow-lg">
                        <div id="p-avatar-placeholder" class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 text-blue-500">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <img id="p-avatar-img" src="" class="w-full h-full object-cover hidden">
                    </div>
                    <img id="p-logo-img" src="" class="h-8 object-contain mx-auto mt-2 hidden">
                </div>

                <!-- Info -->
                <div id="p-info" class="text-center px-6 pt-3 pb-4">
                    <h2 id="p-name" class="text-2xl font-bold text-gray-900">الاسم الكامل</h2>
                    <p id="p-job" class="text-sm font-semibold mt-1 hidden" style="color: #2563EB;"></p>
                    <p id="p-company" class="text-sm text-gray-500 hidden"></p>
                    <p id="p-bio" class="text-sm text-gray-600 mt-3 leading-relaxed hidden"></p>
                </div>

                <!-- Contact Buttons -->
                <div id="p-contacts" class="px-6 pb-3 grid grid-cols-2 gap-2"></div>

                <!-- Address -->
                <div id="p-address-wrap" class="px-6 pb-3 hidden">
                    <div class="bg-gray-50 rounded-xl p-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span id="p-address" class="text-xs text-gray-600"></span>
                    </div>
                </div>

                <!-- Social Links -->
                <div id="p-socials" class="px-6 pb-4 flex justify-center gap-2 flex-wrap"></div>

                <!-- Action Buttons -->
                <div class="px-6 pb-5 space-y-2">
                    <div id="p-save-contact" class="w-full text-center rounded-xl py-3 font-bold text-sm text-white cursor-pointer transition hover:opacity-90" style="background: #2563EB;">
                        حفظ جهة الاتصال
                    </div>
                    <div class="w-full bg-gray-100 text-center rounded-xl py-2.5 font-medium text-xs text-gray-600 cursor-pointer transition hover:bg-gray-200 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        مشاركة البطاقة
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center py-3 border-t">
                    <p class="text-[10px] text-gray-300">بطاقة رقمية من <span class="text-blue-400">معروف</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════ LOADING OVERLAY ═══════════════ -->
    <div id="loading" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[100] hidden backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 text-center shadow-2xl">
            <div class="animate-spin w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full mx-auto"></div>
            <p class="mt-4 text-gray-700 font-bold">جاري الحفظ...</p>
            <p class="text-xs text-gray-400 mt-1">يرجى الانتظار</p>
        </div>
    </div>

    <!-- ═══════════════ QR LIBRARY ═══════════════ -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <!-- ═══════════════ MAIN SCRIPT ═══════════════ -->
    <script>
    // ══════════════════════════════════════
    //  STATE MANAGEMENT
    // ══════════════════════════════════════
    const isEditing = {{ $card ? 'true' : 'false' }};
    const cardId = {{ $card ? $card->id : 'null' }};
    let hasUnsavedChanges = false;
    let lastSavedState = '';

    let state = {
        primaryColor: '#2563EB',
        bgStyle: 'gradient',
        font: 'Cairo',
        borderRadius: 16,
        shadow: 'lg',
        templateId: null,
        layout: 'centered',
        templateLayout: 'standard',
        btnStyle: 'outline',
        profileImage: null,
        coverImage: null,
        logoImage: null,
    };

    let historyStack = [];
    let historyIndex = -1;

    function pushState() {
        const snap = JSON.stringify(state);
        historyStack = historyStack.slice(0, historyIndex + 1);
        historyStack.push(snap);
        historyIndex++;
        if (historyStack.length > 50) { historyStack.shift(); historyIndex--; }
        updateUndoRedoButtons();
        markUnsaved();
    }

    function undo() {
        if (historyIndex > 0) { historyIndex--; state = JSON.parse(historyStack[historyIndex]); applyState(); updateUndoRedoButtons(); }
    }
    function redo() {
        if (historyIndex < historyStack.length - 1) { historyIndex++; state = JSON.parse(historyStack[historyIndex]); applyState(); updateUndoRedoButtons(); }
    }
    function updateUndoRedoButtons() {
        document.getElementById('btn-undo').disabled = historyIndex <= 0;
        document.getElementById('btn-redo').disabled = historyIndex >= historyStack.length - 1;
    }

    function markUnsaved() {
        hasUnsavedChanges = true;
        document.getElementById('save-dot').className = 'w-2 h-2 rounded-full bg-amber-400';
        document.getElementById('save-text').textContent = 'تغييرات غير محفوظة';
    }
    function markSaved() {
        hasUnsavedChanges = false;
        document.getElementById('save-dot').className = 'w-2 h-2 rounded-full bg-green-400';
        document.getElementById('save-text').textContent = 'تم الحفظ';
        setTimeout(() => {
            if (!hasUnsavedChanges) { document.getElementById('save-dot').className = 'w-2 h-2 rounded-full bg-gray-300'; document.getElementById('save-text').textContent = 'جاهز'; }
        }, 3000);
    }

    function applyState() {
        setPrimaryColor(state.primaryColor, false);
        setBorderRadius(state.borderRadius, false);
        setBgStyle(state.bgStyle, false);
        setFont(state.font, false);
        setLayout(state.layout, false);
        setBtnStyle(state.btnStyle, false);
        setShadow(state.shadow, false);
        if (state.templateLayout) applyLayoutStyle(state.templateLayout);
        updatePreview();
    }

    // ══════════════════════════════════════
    //  TABS
    // ══════════════════════════════════════
    function switchTab(tab) {
        ['info', 'style', 'social', 'settings'].forEach(t => {
            const panel = document.getElementById('panel-' + t);
            const btn = document.querySelector(`[data-tab="${t}"]`);
            if (t === tab) { panel.classList.remove('hidden'); btn.classList.add('active'); btn.classList.remove('text-gray-500'); }
            else { panel.classList.add('hidden'); btn.classList.remove('active'); btn.classList.add('text-gray-500'); }
        });
    }

    // ══════════════════════════════════════
    //  PREVIEW MODES
    // ══════════════════════════════════════
    function setPreview(mode) {
        const card = document.getElementById('card-preview');
        const w = { mobile: '380px', tablet: '520px', desktop: '680px' };
        card.style.width = w[mode];
        ['mobile','tablet','desktop'].forEach(m => {
            const b = document.getElementById('btn-' + m);
            if (m === mode) { b.className = 'px-2.5 py-1 rounded-md text-xs font-medium bg-white shadow text-blue-600 transition'; }
            else { b.className = 'px-2.5 py-1 rounded-md text-xs font-medium text-gray-500 transition'; }
        });
    }

    // ══════════════════════════════════════
    //  STYLE CONTROLS
    // ══════════════════════════════════════
    function setPrimaryColor(color, save = true) {
        state.primaryColor = color;
        document.getElementById('custom-color').value = color;
        document.getElementById('color-hex').value = color;
        setBgStyle(state.bgStyle, false);
        document.getElementById('p-job').style.color = color;
        document.getElementById('p-save-contact').style.background = color;
        document.querySelectorAll('.contact-btn-preview').forEach(b => {
            if (state.btnStyle === 'outline') { b.style.borderColor = color; b.style.color = color; b.style.background = 'transparent'; }
            else if (state.btnStyle === 'filled') { b.style.background = color; b.style.color = '#fff'; b.style.borderColor = color; }
            else { b.style.background = color + '15'; b.style.color = color; b.style.borderColor = 'transparent'; }
        });
        document.querySelectorAll('.social-icon-preview').forEach(i => { i.style.background = color + '15'; i.querySelector('svg').style.fill = color; });
        if (save) pushState();
    }

    function adjustColor(hex, amt) {
        let r = parseInt(hex.slice(1,3),16), g = parseInt(hex.slice(3,5),16), b = parseInt(hex.slice(5,7),16);
        return '#' + [r,g,b].map(c => Math.min(255, c + amt).toString(16).padStart(2,'0')).join('');
    }

    function setBgStyle(style, save = true) {
        state.bgStyle = style;
        const cover = document.getElementById('p-cover');
        const c = state.primaryColor;
        if (style === 'solid') cover.style.background = c;
        else if (style === 'gradient') cover.style.background = `linear-gradient(135deg, ${c}, ${adjustColor(c, 50)})`;
        else if (style === 'pattern') cover.style.background = `repeating-linear-gradient(45deg, ${c}, ${c} 10px, ${adjustColor(c, 15)} 10px, ${adjustColor(c, 15)} 20px)`;
        else if (style === 'dark') cover.style.background = `linear-gradient(135deg, #1f2937, ${c})`;
        ['gradient','solid','pattern','dark'].forEach(s => {
            const el = document.getElementById('bg-' + s);
            if (el) { if (s === style) el.classList.add('active'); else el.classList.remove('active'); }
        });
        if (save) pushState();
    }

    function setFont(font, save = true) {
        state.font = font;
        document.getElementById('card-preview').style.fontFamily = `'${font}', sans-serif`;
        document.querySelectorAll('[id^="font-"]').forEach(b => { if (b.id === 'font-' + font) b.classList.add('active'); else b.classList.remove('active'); });
        if (save) pushState();
    }

    function setBorderRadius(val, save = true) {
        state.borderRadius = parseInt(val);
        document.getElementById('card-preview').style.borderRadius = val + 'px';
        document.getElementById('range-radius').value = val;
        if (save) pushState();
    }

    function setShadow(level, save = true) {
        state.shadow = level;
        const shadows = { none: 'none', sm: '0 1px 3px rgba(0,0,0,0.1)', lg: '0 10px 25px rgba(0,0,0,0.15)', '2xl': '0 25px 50px rgba(0,0,0,0.25)' };
        document.getElementById('card-preview').style.boxShadow = shadows[level];
        ['none','sm','lg','2xl'].forEach(s => { const el = document.getElementById('shadow-' + s); if (el) { if (s === level) el.classList.add('active'); else el.classList.remove('active'); }});
        if (save) pushState();
    }

    function setLayout(layout, save = true) {
        state.layout = layout;
        const info = document.getElementById('p-info');
        const profileSection = document.getElementById('p-profile-section');
        if (layout === 'centered') { info.style.textAlign = 'center'; profileSection.style.textAlign = 'center'; }
        else if (layout === 'right') { info.style.textAlign = 'right'; profileSection.style.textAlign = 'right'; }
        else { info.style.textAlign = 'right'; profileSection.style.textAlign = 'right'; }
        ['centered','right','modern'].forEach(l => { const el = document.getElementById('layout-' + l); if (el) { if (l === layout) el.classList.add('active'); else el.classList.remove('active'); }});
        if (save) pushState();
    }

    function setBtnStyle(style, save = true) {
        state.btnStyle = style;
        ['outline','filled','soft'].forEach(s => { const el = document.getElementById('btnstyle-' + s); if (el) { if (s === style) el.classList.add('active'); else el.classList.remove('active'); }});
        updatePreview();
        if (save) pushState();
    }

    function applyLayoutStyle(templateLayout) {
        state.templateLayout = templateLayout;
        const card = document.getElementById('card-preview');
        const cover = document.getElementById('p-cover');
        const profileSection = document.getElementById('p-profile-section');
        const info = document.getElementById('p-info');
        const footer = card.querySelector('.border-t');
        const c = state.primaryColor;

        // Reset common styles
        card.style.background = '#fff';
        card.className = 'preview-card bg-white w-[380px] min-h-[500px] rounded-2xl overflow-hidden relative';
        cover.style.height = '160px';
        cover.className = 'h-40 relative overflow-hidden';
        profileSection.className = 'relative px-6 -mt-16 text-center';
        info.className = 'text-center px-6 pt-3 pb-4';
        if (footer) { footer.style.background = ''; footer.style.borderColor = ''; }

        // Remove any layout-specific injected elements
        card.querySelectorAll('.layout-badge').forEach(e => e.remove());

        switch (templateLayout) {
            case 'bold':
                // Professional: dark glass morphism
                card.style.background = '#111827';
                card.style.color = '#f9fafb';
                card.querySelectorAll('#p-name').forEach(e => e.style.color = '#f9fafb');
                card.querySelectorAll('#p-company').forEach(e => e.style.color = '#9ca3af');
                card.querySelectorAll('#p-bio').forEach(e => e.style.color = '#d1d5db');
                cover.style.background = 'linear-gradient(135deg, #1f2937, ' + c + ')';
                if (footer) { footer.style.background = '#0f172a'; footer.style.borderColor = '#1f2937'; }
                break;

            case 'modern': {
                // Modern: accent bar at top
                const accentBar = document.createElement('div');
                accentBar.className = 'layout-badge';
                accentBar.style.cssText = `position:absolute;top:0;left:0;right:0;height:4px;background:${c};z-index:50;`;
                card.prepend(accentBar);
                card.style.background = '#1e293b';
                card.style.color = '#e2e8f0';
                card.querySelectorAll('#p-name').forEach(e => e.style.color = '#f1f5f9');
                card.querySelectorAll('#p-company').forEach(e => e.style.color = '#94a3b8');
                card.querySelectorAll('#p-bio').forEach(e => e.style.color = '#cbd5e1');
                if (footer) { footer.style.background = '#0f172a'; footer.style.borderColor = '#334155'; }
                break;
            }

            case 'elegant':
                // Luxury: gold-themed white card, green deep background canvas
                card.style.background = '#ffffff';
                card.style.border = '1px solid rgba(197, 164, 126, 0.2)';
                card.style.borderRadius = '32px';
                card.querySelectorAll('#p-name').forEach(e => e.style.color = '#1a1a1a');
                card.querySelectorAll('#p-job').forEach(e => e.style.color = c);
                document.querySelector('.card-canvas').style.background = 'linear-gradient(135deg, #2D5A3D, #1a3a2a)';
                if (footer) { footer.style.borderColor = 'rgba(197, 164, 126, 0.2)'; }
                break;

            case 'classic': {
                // Classic: cream, serif, gold line
                card.style.background = '#ffffff';
                card.style.borderRadius = '4px';
                card.style.border = '1px solid #E8E4DF';
                const goldLine = document.createElement('div');
                goldLine.className = 'layout-badge';
                goldLine.style.cssText = `height:3px;background:linear-gradient(90deg,${c},#D4A843,${c});`;
                card.prepend(goldLine);
                document.querySelector('.card-canvas').style.background = '#FAF8F5';
                card.querySelectorAll('#p-name').forEach(e => { e.style.color = '#1A1A1A'; e.style.fontFamily = "'Playfair Display', serif"; });
                if (footer) { footer.style.background = '#FAF8F5'; footer.style.borderColor = '#E8E4DF'; }
                break;
            }

            case 'minimal':
                // Minimal: light colorful, gradient header
                card.style.background = '#ffffff';
                card.style.borderRadius = '24px';
                cover.style.borderRadius = '24px 24px 0 0';
                document.querySelector('.card-canvas').style.background = 'linear-gradient(135deg, ' + c + '08, #f5f3ff08)';
                card.querySelectorAll('#p-name').forEach(e => e.style.color = '#1f2937');
                break;

            default: // standard
                card.style.background = '#ffffff';
                card.style.borderRadius = state.borderRadius + 'px';
                document.querySelector('.card-canvas').style.background = '';
                card.querySelectorAll('#p-name').forEach(e => { e.style.color = '#111827'; e.style.fontFamily = ''; });
                card.querySelectorAll('#p-company').forEach(e => e.style.color = '#6b7280');
                card.querySelectorAll('#p-bio').forEach(e => e.style.color = '#4b5563');
                if (footer) { footer.style.background = ''; footer.style.borderColor = ''; }
                break;
        }
    }

    function selectTemplate(id) {
        state.templateId = id;
        const el = document.querySelector(`.template-opt[data-id="${id}"]`);
        document.querySelectorAll('.template-opt').forEach(e => e.classList.toggle('active', e.dataset.id == id));
        if (!el) return;

        const hasHtml = el.dataset.hasHtml === '1';

        if (hasHtml) {
            // Show iframe preview with actual HTML template
            loadHtmlTemplatePreview(id);
        } else {
            // Restore default preview if switching from HTML template
            restoreDefaultPreview();
        }

        try {
            const cfg = JSON.parse(el.dataset.config || '{}');
            if (cfg.colors?.primary) setPrimaryColor(cfg.colors.primary, false);
            if (cfg.font) setFont(cfg.font, false);
            if (cfg.layout) {
                state.templateLayout = cfg.layout;
                if (!hasHtml) {
                    const layoutMap = { standard: 'centered', elegant: 'centered', modern: 'modern', classic: 'centered', minimal: 'centered', bold: 'right' };
                    setLayout(layoutMap[cfg.layout] || 'centered', false);
                    applyLayoutStyle(cfg.layout);
                }
            }
            if (cfg.styles?.borderRadius) setBorderRadius(parseInt(cfg.styles.borderRadius), false);
            if (cfg.styles?.shadow) setShadow(cfg.styles.shadow, false);
            if (cfg.styles?.btnStyle) {
                const btnMap = { rounded: 'soft', pill: 'filled', square: 'outline' };
                setBtnStyle(btnMap[cfg.styles.btnStyle] || 'outline', false);
            }
            pushState();
        } catch(e) { setPrimaryColor('#2563EB'); }
    }

    let htmlPreviewActive = false;
    let defaultPreviewHTML = null;

    async function loadHtmlTemplatePreview(templateId) {
        const canvas = document.querySelector('.card-canvas');
        const preview = document.getElementById('card-preview');

        // Save default preview HTML on first switch
        if (!defaultPreviewHTML) {
            defaultPreviewHTML = preview.outerHTML;
        }

        // Build form data with current card info
        const formData = new FormData();
        formData.append('template_id', templateId);
        formData.append('full_name', document.getElementById('f-name')?.value || 'الاسم الكامل');
        formData.append('job_title', document.getElementById('f-job')?.value || 'المسمى الوظيفي');
        formData.append('company', document.getElementById('f-company')?.value || 'الشركة');
        formData.append('bio', document.getElementById('f-bio')?.value || '');
        formData.append('phone', document.getElementById('f-phone')?.value || '+966500000000');
        formData.append('email', document.getElementById('f-email')?.value || 'email@example.com');
        formData.append('website', document.getElementById('f-website')?.value || '');
        if (state.profileImage) formData.append('profile_image', state.profileImage);
        if (state.coverImage) formData.append('cover_image', state.coverImage);
        if (state.logoImage) formData.append('logo', state.logoImage);

        // Social links
        document.querySelectorAll('.social-input').forEach(input => {
            if (input.value) formData.append(input.dataset.platform, input.value);
        });

        try {
            const res = await fetch('{{ route("customer.builder.preview-template") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            });
            const html = await res.text();

            // Replace preview with iframe
            const iframe = document.createElement('iframe');
            iframe.id = 'html-template-iframe';
            iframe.style.cssText = 'width:420px;height:calc(100vh - 100px);border:none;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.15);background:#fff;';
            iframe.srcdoc = html;

            // Replace existing preview or iframe
            const existingIframe = document.getElementById('html-template-iframe');
            if (existingIframe) {
                existingIframe.srcdoc = html;
            } else {
                preview.style.display = 'none';
                canvas.querySelector('.flex-1') || canvas.appendChild(iframe);
                preview.parentNode.insertBefore(iframe, preview.nextSibling);
            }

            htmlPreviewActive = true;
        } catch (e) {
            console.error('Failed to load template preview:', e);
        }
    }

    function restoreDefaultPreview() {
        if (!htmlPreviewActive) return;
        const iframe = document.getElementById('html-template-iframe');
        if (iframe) iframe.remove();
        const preview = document.getElementById('card-preview');
        if (preview) preview.style.display = '';
        htmlPreviewActive = false;
    }

    // ══════════════════════════════════════
    //  IMAGE UPLOAD (to server)
    // ══════════════════════════════════════
    async function uploadImage(input, type) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) { showToast('حجم الملف يجب أن يكون أقل من 2MB', 'error'); return; }

        const formData = new FormData();
        formData.append('image', file);
        formData.append('type', type);

        const zone = document.getElementById('zone-' + type);
        zone.style.opacity = '0.5';

        try {
            const res = await fetch('{{ route("customer.builder.upload-image") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                zone.classList.add('has-image');
                const thumb = document.getElementById('thumb-' + type);
                if (type === 'profile') {
                    thumb.innerHTML = `<img src="${data.url}" class="w-full h-full object-cover">`;
                    state.profileImage = data.url;
                    document.getElementById('p-avatar-placeholder').classList.add('hidden');
                    document.getElementById('p-avatar-img').src = data.url;
                    document.getElementById('p-avatar-img').classList.remove('hidden');
                } else if (type === 'cover') {
                    thumb.innerHTML = `<img src="${data.url}" class="w-full h-full object-cover">`;
                    state.coverImage = data.url;
                    document.getElementById('p-cover-img').src = data.url;
                    document.getElementById('p-cover-img').classList.remove('hidden');
                } else if (type === 'logo') {
                    thumb.innerHTML = `<img src="${data.url}" class="w-full h-full object-contain">`;
                    state.logoImage = data.url;
                    document.getElementById('p-logo-img').src = data.url;
                    document.getElementById('p-logo-img').classList.remove('hidden');
                }
                pushState();
                showToast('تم رفع الصورة بنجاح', 'success');
            }
        } catch (e) {
            showToast('فشل رفع الصورة', 'error');
        } finally {
            zone.style.opacity = '1';
        }
    }

    // ══════════════════════════════════════
    //  UPDATE PREVIEW
    // ══════════════════════════════════════
    const socialIcons = {
        twitter: 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z',
        instagram: 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z',
        linkedin: 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
        snapchat: 'M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.217-.937 1.407-5.965 1.407-5.965s-.359-.72-.359-1.781c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641 0 12.017 0z',
        tiktok: 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z',
        youtube: 'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
        facebook: 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
        github: 'M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12',
        telegram: 'M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z',
        behance: 'M22 7h-7V5h7v2zm1.726 10c-.442 1.297-2.029 3-5.101 3-3.074 0-5.564-1.729-5.564-5.675 0-3.91 2.325-5.92 5.466-5.92 3.082 0 4.964 1.782 5.375 4.426.078.506.109 1.188.095 2.14H15.97c.13 3.211 3.483 3.312 4.588 2.029h3.168zm-7.686-4h4.965c-.105-1.547-1.136-2.219-2.477-2.219-1.466 0-2.277.768-2.488 2.219zm-9.574 6.988H0V5.021h6.953c5.476.081 5.58 5.444 2.72 6.906 3.461 1.26 3.577 8.061-3.207 8.061zM3 11h3.584c2.508 0 2.906-3-.312-3H3v3zm3.391 3H3v3.016h3.341c3.055 0 2.868-3.016.05-3.016z',
        dribbble: 'M12 24C5.385 24 0 18.615 0 12S5.385 0 12 0s12 5.385 12 12-5.385 12-12 12zm10.12-10.358c-.35-.11-3.17-.953-6.384-.438 1.34 3.684 1.887 6.684 1.992 7.308 2.3-1.555 3.936-4.02 4.395-6.87zm-6.115 7.808c-.153-.9-.75-4.032-2.19-7.77l-.066.02c-5.79 2.015-7.86 6.025-8.04 6.4 1.73 1.358 3.92 2.166 6.29 2.166 1.42 0 2.77-.29 4-.81zm-11.62-2.58c.232-.4 3.045-5.055 8.332-6.765.135-.045.27-.084.405-.12-.26-.585-.54-1.167-.832-1.74C7.17 11.775 2.206 11.71 1.756 11.7l-.004.312c0 2.633.998 5.037 2.634 6.855zm-2.42-8.955c.46.008 4.683.026 9.477-1.248-1.698-3.018-3.53-5.558-3.8-5.928-2.868 1.35-5.01 3.99-5.676 7.17zM9.6 2.052c.282.38 2.145 2.914 3.822 6 3.645-1.365 5.19-3.44 5.373-3.702C16.904 2.64 14.56 1.594 12 1.594c-.82 0-1.617.1-2.4.285zm10.335 3.483c-.218.29-1.91 2.493-5.724 4.04.24.49.47.985.68 1.486.08.18.15.36.22.53 3.41-.43 6.8.26 7.14.33-.02-2.42-.88-4.64-2.31-6.38z',
    };

    function updatePreview() {
        const v = id => document.getElementById(id)?.value || '';

        // Name & info
        document.getElementById('p-name').textContent = v('f-name') || 'الاسم الكامل';
        const job = v('f-job');
        const jobEl = document.getElementById('p-job');
        jobEl.textContent = job; jobEl.classList.toggle('hidden', !job);

        const company = v('f-company');
        const compEl = document.getElementById('p-company');
        compEl.textContent = company; compEl.classList.toggle('hidden', !company);

        const bio = v('f-bio');
        const bioEl = document.getElementById('p-bio');
        bioEl.textContent = bio; bioEl.classList.toggle('hidden', !bio);

        const address = v('f-address');
        document.getElementById('p-address').textContent = address;
        document.getElementById('p-address-wrap').classList.toggle('hidden', !address);

        // Contact buttons
        const phone = v('f-phone'), email = v('f-email'), whatsapp = v('f-whatsapp'), website = v('f-website');
        const c = state.primaryColor;
        let btnClass, btnCss;
        if (state.btnStyle === 'outline') { btnCss = `border: 2px solid ${c}; color: ${c}; background: transparent;`; }
        else if (state.btnStyle === 'filled') { btnCss = `background: ${c}; color: #fff; border: 2px solid ${c};`; }
        else { btnCss = `background: ${c}15; color: ${c}; border: 2px solid transparent;`; }

        let contacts = '';
        if (phone) contacts += `<a class="contact-btn-preview flex items-center justify-center gap-2 rounded-xl py-2.5 px-3 text-xs font-semibold transition" style="${btnCss}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>اتصال</a>`;
        if (whatsapp) contacts += `<a class="contact-btn-preview flex items-center justify-center gap-2 rounded-xl py-2.5 px-3 text-xs font-semibold bg-green-50 transition" style="border: 2px solid #25D366; color: #25D366;"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>واتساب</a>`;
        if (email) contacts += `<a class="contact-btn-preview flex items-center justify-center gap-2 rounded-xl py-2.5 px-3 text-xs font-semibold transition" style="${btnCss}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>بريد</a>`;
        if (website) contacts += `<a class="contact-btn-preview flex items-center justify-center gap-2 rounded-xl py-2.5 px-3 text-xs font-semibold transition" style="${btnCss}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>الموقع</a>`;
        document.getElementById('p-contacts').innerHTML = contacts;

        // Social icons
        const platforms = ['twitter','instagram','linkedin','snapchat','tiktok','youtube','facebook','telegram','github','behance','dribbble'];
        let socials = '';
        platforms.forEach(p => {
            const url = document.getElementById('social-' + p)?.value;
            if (url && socialIcons[p]) {
                socials += `<a class="social-icon-preview w-9 h-9 rounded-full flex items-center justify-center transition hover:scale-110" style="background:${c}15" title="${p}"><svg class="w-4 h-4" style="fill:${c}" viewBox="0 0 24 24"><path d="${socialIcons[p]}"/></svg></a>`;
            }
        });
        document.getElementById('p-socials').innerHTML = socials;
    }

    // ══════════════════════════════════════
    //  SAVE CARD
    // ══════════════════════════════════════
    async function saveCard(publish = false) {
        const name = document.getElementById('f-name').value;
        if (!name) { showToast('يرجى إدخال الاسم الكامل', 'error'); switchTab('info'); document.getElementById('f-name').focus(); return; }

        document.getElementById('loading').classList.remove('hidden');

        const socialLinks = {};
        ['twitter','instagram','linkedin','snapchat','tiktok','youtube','facebook','telegram','github','behance','dribbble'].forEach(p => {
            const val = document.getElementById('social-' + p)?.value;
            if (val) socialLinks[p] = val;
        });

        const data = {
            card_id: cardId,
            title: document.getElementById('f-title').value || name,
            full_name: name,
            job_title: document.getElementById('f-job').value || null,
            company: document.getElementById('f-company').value || null,
            bio: document.getElementById('f-bio').value || null,
            email: document.getElementById('f-email').value || null,
            phone: document.getElementById('f-phone').value || null,
            whatsapp: document.getElementById('f-whatsapp').value || null,
            website: document.getElementById('f-website').value || null,
            address: document.getElementById('f-address').value || null,
            template_id: state.templateId,
            design_data: JSON.stringify(state),
            social_links: socialLinks,
            is_public: publish || document.getElementById('f-public').checked,
            password: document.getElementById('f-password').value || null,
            expires_at: document.getElementById('f-expires').value || null,
            slug: document.getElementById('f-slug').value || null,
        };

        try {
            const res = await fetch('{{ route("customer.builder.save") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify(data),
            });
            const result = await res.json();

            if (result.success) {
                markSaved();
                // Update QR
                generateQR(result.public_url);
                document.getElementById('btn-download-qr').style.display = 'inline';
                // Update slug display
                if (result.slug) document.getElementById('f-slug').value = result.slug;

                if (publish) {
                    showToast('تم نشر البطاقة بنجاح!', 'success');
                    setTimeout(() => { window.location.href = result.url; }, 1500);
                } else {
                    showToast('تم حفظ المسودة', 'success');
                    // Update cardId for subsequent saves
                    if (!cardId && result.card_id) {
                        window.history.replaceState({}, '', '{{ url("customer/builder") }}/' + result.card_id + '/edit');
                    }
                }
            } else {
                const errors = result.errors ? Object.values(result.errors).flat().join('\n') : (result.message || 'حدث خطأ');
                showToast(errors, 'error');
            }
        } catch (e) {
            showToast('خطأ في الاتصال بالسيرفر', 'error');
        } finally {
            document.getElementById('loading').classList.add('hidden');
        }
    }

    // ══════════════════════════════════════
    //  QR CODE
    // ══════════════════════════════════════
    let qrInstance = null;
    function generateQR(url) {
        const container = document.getElementById('qr-code');
        container.innerHTML = '';
        qrInstance = new QRCode(container, { text: url, width: 140, height: 140, colorDark: '#1f2937', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.H });
    }
    function downloadQR() {
        const canvas = document.querySelector('#qr-code canvas');
        if (!canvas) return;
        const link = document.createElement('a');
        link.download = 'qr-code.png';
        link.href = canvas.toDataURL();
        link.click();
    }

    // ══════════════════════════════════════
    //  UTILITIES
    // ══════════════════════════════════════
    function showToast(msg, type = 'info') {
        const existing = document.querySelector('.toast');
        if (existing) existing.remove();
        const colors = { success: 'bg-green-600 text-white', error: 'bg-red-600 text-white', info: 'bg-gray-800 text-white' };
        const toast = document.createElement('div');
        toast.className = `toast ${colors[type]}`;
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function previewPublic() {
        const slug = document.getElementById('f-slug').value;
        if (slug) window.open('{{ url("/card") }}/' + slug, '_blank');
        else showToast('يرجى حفظ البطاقة أولاً', 'info');
    }

    // ══════════════════════════════════════
    //  KEYBOARD SHORTCUTS
    // ══════════════════════════════════════
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            if (e.key === 'z' && !e.shiftKey) { e.preventDefault(); undo(); }
            else if (e.key === 'z' && e.shiftKey) { e.preventDefault(); redo(); }
            else if (e.key === 'y') { e.preventDefault(); redo(); }
            else if (e.key === 's') { e.preventDefault(); saveCard(false); }
        }
    });

    // Unsaved changes warning
    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) { e.preventDefault(); e.returnValue = ''; }
    });

    // ══════════════════════════════════════
    //  INITIALIZATION
    // ══════════════════════════════════════
    @if($card)
    // Pre-fill editing data
    (function() {
        const card = @json($card);
        document.getElementById('f-name').value = card.full_name || '';
        document.getElementById('f-title').value = card.title || '';
        document.getElementById('f-job').value = card.job_title || '';
        document.getElementById('f-company').value = card.company || '';
        document.getElementById('f-bio').value = card.bio || '';
        document.getElementById('f-email').value = card.email || '';
        document.getElementById('f-phone').value = card.phone || '';
        document.getElementById('f-whatsapp').value = card.whatsapp || '';
        document.getElementById('f-website').value = card.website || '';
        document.getElementById('f-address').value = card.address || '';
        document.getElementById('f-slug').value = card.slug || '';
        document.getElementById('f-password').value = card.password || '';
        document.getElementById('f-public').checked = !!card.is_public;

        if (card.expires_at) {
            const d = new Date(card.expires_at);
            document.getElementById('f-expires').value = d.toISOString().slice(0, 16);
        }

        // Social links
        if (card.social_links) {
            card.social_links.forEach(link => {
                const input = document.getElementById('social-' + link.platform);
                if (input) input.value = link.url || '';
            });
        }

        // Design data
        if (card.design_data) {
            try {
                const d = typeof card.design_data === 'string' ? JSON.parse(card.design_data) : card.design_data;
                Object.assign(state, d);
            } catch(e) {}
        }

        if (card.template_id) state.templateId = card.template_id;

        // Images
        if (card.profile_image) {
            state.profileImage = '/storage/' + card.profile_image;
            document.getElementById('p-avatar-placeholder').classList.add('hidden');
            document.getElementById('p-avatar-img').src = state.profileImage;
            document.getElementById('p-avatar-img').classList.remove('hidden');
            document.getElementById('thumb-profile').innerHTML = `<img src="${state.profileImage}" class="w-full h-full object-cover">`;
            document.getElementById('zone-profile').classList.add('has-image');
        }
        if (card.cover_image) {
            state.coverImage = '/storage/' + card.cover_image;
            document.getElementById('p-cover-img').src = state.coverImage;
            document.getElementById('p-cover-img').classList.remove('hidden');
            document.getElementById('thumb-cover').innerHTML = `<img src="${state.coverImage}" class="w-full h-full object-cover">`;
            document.getElementById('zone-cover').classList.add('has-image');
        }
        if (card.logo) {
            state.logoImage = '/storage/' + card.logo;
            document.getElementById('p-logo-img').src = state.logoImage;
            document.getElementById('p-logo-img').classList.remove('hidden');
            document.getElementById('thumb-logo').innerHTML = `<img src="${state.logoImage}" class="w-full h-full object-contain">`;
            document.getElementById('zone-logo').classList.add('has-image');
        }

        // Generate QR for existing card
        generateQR('{{ $card ? route("public.cards.show", $card->slug) : "" }}');
        document.getElementById('btn-download-qr').style.display = 'inline';
    })();
    @endif

    // Apply initial state and render
    applyState();
    pushState();
    hasUnsavedChanges = false;
    document.getElementById('save-dot').className = 'w-2 h-2 rounded-full bg-gray-300';
    document.getElementById('save-text').textContent = 'جاهز';
    </script>
</body>
</html>
