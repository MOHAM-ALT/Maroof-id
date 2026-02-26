@extends('layouts.app')
@section('title', 'إنشاء هوية جديدة')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('customer.brand-kit.index') }}" class="text-blue-600 hover:text-blue-700 text-sm">&larr; العودة للهويات</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">إنشاء هوية علامة تجارية</h1>
    </div>

    <form method="POST" action="{{ route('customer.brand-kit.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Basic Info -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">المعلومات الأساسية</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">اسم الهوية *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الخط</label>
                    <select name="font_family" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="Cairo" {{ old('font_family') == 'Cairo' ? 'selected' : '' }}>Cairo</option>
                        <option value="Tajawal" {{ old('font_family') == 'Tajawal' ? 'selected' : '' }}>Tajawal</option>
                        <option value="Almarai" {{ old('font_family') == 'Almarai' ? 'selected' : '' }}>Almarai</option>
                        <option value="IBM Plex Sans Arabic" {{ old('font_family') == 'IBM Plex Sans Arabic' ? 'selected' : '' }}>IBM Plex Sans Arabic</option>
                        <option value="Noto Sans Arabic" {{ old('font_family') == 'Noto Sans Arabic' ? 'selected' : '' }}>Noto Sans Arabic</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Colors -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">الألوان</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">أساسي</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="primary_color" value="{{ old('primary_color', '#1d4ed8') }}" class="w-10 h-10 rounded border-0 cursor-pointer">
                        <input type="text" value="{{ old('primary_color', '#1d4ed8') }}" class="w-full border border-gray-300 rounded-lg px-2 py-1 text-xs font-mono" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">ثانوي</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="secondary_color" value="{{ old('secondary_color', '#3b82f6') }}" class="w-10 h-10 rounded border-0 cursor-pointer">
                        <input type="text" value="{{ old('secondary_color', '#3b82f6') }}" class="w-full border border-gray-300 rounded-lg px-2 py-1 text-xs font-mono" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">تمييز</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="accent_color" value="{{ old('accent_color', '#f59e0b') }}" class="w-10 h-10 rounded border-0 cursor-pointer">
                        <input type="text" value="{{ old('accent_color', '#f59e0b') }}" class="w-full border border-gray-300 rounded-lg px-2 py-1 text-xs font-mono" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">نص</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="text_color" value="{{ old('text_color', '#1f2937') }}" class="w-10 h-10 rounded border-0 cursor-pointer">
                        <input type="text" value="{{ old('text_color', '#1f2937') }}" class="w-full border border-gray-300 rounded-lg px-2 py-1 text-xs font-mono" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">خلفية</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="background_color" value="{{ old('background_color', '#ffffff') }}" class="w-10 h-10 rounded border-0 cursor-pointer">
                        <input type="text" value="{{ old('background_color', '#ffffff') }}" class="w-full border border-gray-300 rounded-lg px-2 py-1 text-xs font-mono" readonly>
                    </div>
                </div>
            </div>

            <!-- Live Preview -->
            <div class="mt-6 p-6 rounded-xl border-2 border-dashed border-gray-200" id="colorPreview" style="background-color: #ffffff; color: #1f2937;">
                <div class="text-center">
                    <h3 class="text-xl font-bold" id="previewTitle" style="color: #1d4ed8;">معاينة الألوان</h3>
                    <p class="mt-1" id="previewSubtitle" style="color: #3b82f6;">هذا نموذج لشكل الألوان في البطاقة</p>
                    <span class="inline-block mt-2 px-4 py-1 rounded-full text-white text-sm" id="previewBadge" style="background-color: #f59e0b;">زر المعاينة</span>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">الصور والشعارات</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الشعار</label>
                    <input type="file" name="logo" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">حتى 2MB</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الأيقونة</label>
                    <input type="file" name="icon" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">حتى 1MB</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">صورة الغلاف</label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">حتى 4MB</p>
                </div>
            </div>
        </div>

        <!-- Defaults -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">القيم الافتراضية</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">اسم الشركة الافتراضي</label>
                    <input type="text" name="default_company" value="{{ old('default_company') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الموقع الافتراضي</label>
                    <input type="url" name="default_website" value="{{ old('default_website') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="https://">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">النبذة الافتراضية</label>
                    <textarea name="default_bio" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2">{{ old('default_bio') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" class="w-4 h-4 rounded text-blue-600">
                    <span class="text-sm font-semibold text-gray-700">تعيين كهوية افتراضية</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">حفظ الهوية</button>
            <a href="{{ route('customer.brand-kit.index') }}" class="bg-gray-100 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-200 transition font-semibold">إلغاء</a>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('input', function() {
        this.nextElementSibling.value = this.value;
        updatePreview();
    });
});

function updatePreview() {
    const primary = document.querySelector('[name="primary_color"]').value;
    const secondary = document.querySelector('[name="secondary_color"]').value;
    const accent = document.querySelector('[name="accent_color"]').value;
    const text = document.querySelector('[name="text_color"]').value;
    const bg = document.querySelector('[name="background_color"]').value;

    document.getElementById('colorPreview').style.backgroundColor = bg;
    document.getElementById('colorPreview').style.color = text;
    document.getElementById('previewTitle').style.color = primary;
    document.getElementById('previewSubtitle').style.color = secondary;
    document.getElementById('previewBadge').style.backgroundColor = accent;
}
</script>
@endsection
