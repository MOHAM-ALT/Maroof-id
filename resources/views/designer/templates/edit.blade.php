@extends('layouts.app')

@section('title', 'تعديل القالب - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('designer.templates.index') }}" class="text-purple-600 hover:text-purple-700 text-sm">&larr; العودة للقوالب</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">تعديل القالب: {{ $template->name_ar }}</h1>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('designer.templates.update', $template) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">اسم القالب (عربي) *</label>
                        <input type="text" name="name_ar" value="{{ old('name_ar', $template->name_ar) }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Template Name (English) *</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $template->name_en) }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">التصنيف *</label>
                    <select name="template_category_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('template_category_id', $template->template_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name_ar }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">الوصف (عربي)</label>
                        <textarea name="description_ar" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('description_ar', $template->description_ar) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Description (English)</label>
                        <textarea name="description_en" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('description_en', $template->description_en) }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">السعر (ر.س) *</label>
                        <input type="number" name="price" value="{{ old('price', $template->price) }}" min="0" step="1" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_premium" value="1" {{ old('is_premium', $template->is_premium) ? 'checked' : '' }}
                                   class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                            <span class="text-sm text-gray-700">قالب مميز (Premium)</span>
                        </label>
                    </div>
                </div>

                {{-- HTML Template File --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">ملف HTML للقالب</label>
                    @if($template->html_content)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-2">
                            <p class="text-green-700 text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                يوجد ملف HTML محفوظ ({{ number_format(strlen($template->html_content)) }} حرف)
                            </p>
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition cursor-pointer"
                         onclick="document.getElementById('html_file').click()">
                        <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        <p id="html_file_name" class="text-gray-500 text-sm">{{ $template->html_content ? 'اضغط لتحديث ملف HTML' : 'اضغط لرفع ملف HTML (.html)' }}</p>
                        <input type="file" name="html_file" id="html_file" accept=".html,.htm" class="hidden"
                               onchange="document.getElementById('html_file_name').textContent = this.files[0]?.name || 'اضغط لرفع ملف HTML'">
                    </div>
                    <div class="mt-2 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-blue-700 text-xs font-semibold mb-1">المتغيرات المدعومة:</p>
                        <p class="text-blue-600 text-xs font-mono" dir="ltr">@{{NAME}}, @{{PHONE}}, @{{EMAIL}}, @{{PHOTO}}, @{{BIO}}, @{{JOB_TITLE}}, @{{COMPANY}}, @{{WEBSITE}}, @{{INSTAGRAM}}, @{{LINKEDIN}}, @{{TWITTER}}</p>
                        <p class="text-blue-600 text-xs mt-1">استخدم <code dir="ltr" class="bg-blue-100 px-1 rounded">@{{#if FIELD}}...@{{/if}}</code> لإظهار/إخفاء الأقسام</p>
                    </div>
                </div>

                {{-- Customizable Fields --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الخانات القابلة للتخصيص من المستخدم</label>
                    @php $currentFields = $template->customizable_fields ?? []; @endphp
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach(['primaryColor' => 'اللون الأساسي', 'secondaryColor' => 'اللون الثانوي', 'bgColor' => 'لون الخلفية', 'textColor' => 'لون النص', 'font' => 'الخط', 'borderRadius' => 'حواف مستديرة'] as $field => $label)
                            <label class="flex items-center gap-2 p-2 border rounded-lg cursor-pointer hover:bg-purple-50">
                                <input type="checkbox" name="customizable_fields[]" value="{{ $field }}"
                                       {{ in_array($field, $currentFields) ? 'checked' : '' }}
                                       class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">صورة المعاينة</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition cursor-pointer"
                         onclick="document.getElementById('preview_image').click()">
                        @if($template->preview_image)
                            <img id="preview_img" src="{{ asset('storage/' . $template->preview_image) }}" alt="" class="mx-auto max-h-48 rounded-lg mb-3">
                            <p id="upload_text" class="text-gray-500 text-sm">اضغط لتغيير الصورة</p>
                        @else
                            <img id="preview_img" src="" alt="" class="hidden mx-auto max-h-48 rounded-lg mb-3">
                            <p id="upload_text" class="text-gray-500 text-sm">اضغط لرفع صورة المعاينة</p>
                        @endif
                        <input type="file" name="preview_image" id="preview_image" accept="image/*" class="hidden"
                               onchange="previewImage(this)">
                    </div>
                </div>

                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-bold transition">
                    حفظ التعديلات
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview_img').src = e.target.result;
            document.getElementById('preview_img').classList.remove('hidden');
            document.getElementById('upload_text').textContent = 'اضغط لتغيير الصورة';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
