@extends('layouts.app')
@section('title', 'تعديل هوية - ' . $brandKit->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('customer.brand-kit.index') }}" class="text-blue-600 hover:text-blue-700 text-sm">&larr; العودة للهويات</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">تعديل: {{ $brandKit->name }}</h1>
    </div>

    <form method="POST" action="{{ route('customer.brand-kit.update', $brandKit) }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">المعلومات الأساسية</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">اسم الهوية *</label>
                    <input type="text" name="name" value="{{ old('name', $brandKit->name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الخط</label>
                    <select name="font_family" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        @foreach(['Cairo', 'Tajawal', 'Almarai', 'IBM Plex Sans Arabic', 'Noto Sans Arabic'] as $font)
                        <option value="{{ $font }}" {{ old('font_family', $brandKit->font_family) == $font ? 'selected' : '' }}>{{ $font }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Colors -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">الألوان</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach([
                    'primary_color' => ['label' => 'أساسي', 'default' => $brandKit->primary_color],
                    'secondary_color' => ['label' => 'ثانوي', 'default' => $brandKit->secondary_color],
                    'accent_color' => ['label' => 'تمييز', 'default' => $brandKit->accent_color],
                    'text_color' => ['label' => 'نص', 'default' => $brandKit->text_color],
                    'background_color' => ['label' => 'خلفية', 'default' => $brandKit->background_color],
                ] as $field => $config)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ $config['label'] }}</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="{{ $field }}" value="{{ old($field, $config['default']) }}" class="w-10 h-10 rounded border-0 cursor-pointer">
                        <input type="text" value="{{ old($field, $config['default']) }}" class="w-full border border-gray-300 rounded-lg px-2 py-1 text-xs font-mono" readonly>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 p-6 rounded-xl border-2 border-dashed border-gray-200" id="colorPreview" style="background-color: {{ $brandKit->background_color }}; color: {{ $brandKit->text_color }};">
                <div class="text-center">
                    <h3 class="text-xl font-bold" id="previewTitle" style="color: {{ $brandKit->primary_color }};">معاينة الألوان</h3>
                    <p class="mt-1" id="previewSubtitle" style="color: {{ $brandKit->secondary_color }};">هذا نموذج لشكل الألوان في البطاقة</p>
                    <span class="inline-block mt-2 px-4 py-1 rounded-full text-white text-sm" id="previewBadge" style="background-color: {{ $brandKit->accent_color }};">زر المعاينة</span>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">الصور والشعارات</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الشعار</label>
                    @if($brandKit->logo_path)
                    <img src="{{ asset('storage/' . $brandKit->logo_path) }}" class="w-16 h-16 rounded-lg object-contain border mb-2">
                    @endif
                    <input type="file" name="logo" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الأيقونة</label>
                    @if($brandKit->icon_path)
                    <img src="{{ asset('storage/' . $brandKit->icon_path) }}" class="w-12 h-12 rounded-lg object-contain border mb-2">
                    @endif
                    <input type="file" name="icon" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">صورة الغلاف</label>
                    @if($brandKit->cover_image_path)
                    <img src="{{ asset('storage/' . $brandKit->cover_image_path) }}" class="w-full h-16 rounded-lg object-cover border mb-2">
                    @endif
                    <input type="file" name="cover_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        <!-- Defaults -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">القيم الافتراضية</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">اسم الشركة الافتراضي</label>
                    <input type="text" name="default_company" value="{{ old('default_company', $brandKit->default_company) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الموقع الافتراضي</label>
                    <input type="url" name="default_website" value="{{ old('default_website', $brandKit->default_website) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="https://">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">النبذة الافتراضية</label>
                    <textarea name="default_bio" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2">{{ old('default_bio', $brandKit->default_bio) }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" {{ $brandKit->is_default ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600">
                    <span class="text-sm font-semibold text-gray-700">تعيين كهوية افتراضية</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">حفظ التغييرات</button>
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
