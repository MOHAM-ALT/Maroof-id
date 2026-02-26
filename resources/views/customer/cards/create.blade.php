@extends('layouts.app')

@section('title', 'إنشاء بطاقة جديدة')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('customer.cards.index') }}" class="text-blue-600 hover:text-blue-700 text-sm mb-2 inline-block">→ العودة للبطاقات</a>
            <h1 class="text-3xl font-bold text-gray-900">إنشاء بطاقة جديدة</h1>
            <p class="text-gray-600 mt-1">أنشئ بطاقتك الرقمية الاحترافية</p>
        </div>

        <form method="POST" action="{{ route('customer.cards.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Step 1: Template Selection -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    اختيار القالب
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($templates as $template)
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="template_id" value="{{ $template->id }}" class="peer hidden" {{ old('template_id') == $template->id ? 'checked' : '' }} required>
                        <div class="border-2 border-gray-200 rounded-lg p-3 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 group-hover:border-blue-300">
                            @if($template->preview_image)
                                <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" class="w-full h-32 object-cover rounded mb-2">
                            @else
                                <div class="w-full h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded mb-2 flex items-center justify-center text-white text-3xl font-bold">
                                    {{ mb_substr($template->name, 0, 1) }}
                                </div>
                            @endif
                            <p class="font-semibold text-sm">{{ $template->name }}</p>
                            <p class="text-blue-600 text-sm font-bold">{{ $template->isFree() ? 'مجاني' : $template->price . ' ر.س' }}</p>
                        </div>
                        <div class="absolute top-2 left-2 w-6 h-6 rounded-full bg-blue-500 text-white items-center justify-center text-xs hidden peer-checked:flex">✓</div>
                    </label>
                    @endforeach
                </div>
                @error('template_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            <!-- Step 2: Personal Information -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                    المعلومات الشخصية
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">الاسم الكامل *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="محمد أحمد">
                        @error('full_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">المسمى الوظيفي</label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مدير التسويق">
                        @error('job_title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">الشركة / المؤسسة</label>
                        <input type="text" name="company" value="{{ old('company') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="شركة معروف">
                        @error('company') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">عنوان البطاقة *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="بطاقتي الاحترافية">
                        @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">نبذة تعريفية</label>
                        <textarea name="bio" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="نبذة مختصرة عنك أو عن عملك...">{{ old('bio') }}</textarea>
                        @error('bio') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Step 3: Contact Info -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">3</span>
                    معلومات التواصل
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="email@example.com">
                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">رقم الجوال</label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" dir="ltr"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-left"
                               placeholder="+966 5xx xxx xxx">
                        @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">واتساب</label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" dir="ltr"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-left"
                               placeholder="+966 5xx xxx xxx">
                        @error('whatsapp') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">الموقع الإلكتروني</label>
                        <input type="url" name="website" value="{{ old('website') }}" dir="ltr"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-left"
                               placeholder="https://example.com">
                        @error('website') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">العنوان</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="الرياض، المملكة العربية السعودية">
                        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Step 4: Social Links -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">4</span>
                    روابط التواصل الاجتماعي
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $socialPlatforms = [
                            'twitter' => ['label' => 'X (تويتر)', 'placeholder' => 'https://x.com/username'],
                            'linkedin' => ['label' => 'لينكدإن', 'placeholder' => 'https://linkedin.com/in/username'],
                            'instagram' => ['label' => 'انستقرام', 'placeholder' => 'https://instagram.com/username'],
                            'snapchat' => ['label' => 'سناب شات', 'placeholder' => 'https://snapchat.com/add/username'],
                            'tiktok' => ['label' => 'تيك توك', 'placeholder' => 'https://tiktok.com/@username'],
                            'youtube' => ['label' => 'يوتيوب', 'placeholder' => 'https://youtube.com/@channel'],
                            'facebook' => ['label' => 'فيسبوك', 'placeholder' => 'https://facebook.com/username'],
                            'telegram' => ['label' => 'تيليجرام', 'placeholder' => 'https://t.me/username'],
                        ];
                    @endphp

                    @foreach($socialPlatforms as $platform => $info)
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ $info['label'] }}</label>
                        <input type="url" name="social_links[{{ $platform }}]" value="{{ old("social_links.$platform") }}" dir="ltr"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-left"
                               placeholder="{{ $info['placeholder'] }}">
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Step 5: Images -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">5</span>
                    الصور
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">الصورة الشخصية</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                            <input type="file" name="profile_image" accept="image/*" class="hidden" id="profile_image"
                                   onchange="previewImage(this, 'profile_preview')">
                            <label for="profile_image" class="cursor-pointer">
                                <img id="profile_preview" src="" alt="" class="w-24 h-24 rounded-full mx-auto mb-2 object-cover hidden">
                                <div id="profile_placeholder" class="w-24 h-24 rounded-full mx-auto mb-2 bg-gray-100 flex items-center justify-center text-gray-400 text-3xl">
                                    +
                                </div>
                                <p class="text-gray-500 text-xs">اضغط لرفع الصورة</p>
                                <p class="text-gray-400 text-xs">PNG, JPG حتى 2MB</p>
                            </label>
                        </div>
                        @error('profile_image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">صورة الغلاف</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                            <input type="file" name="cover_image" accept="image/*" class="hidden" id="cover_image"
                                   onchange="previewImage(this, 'cover_preview')">
                            <label for="cover_image" class="cursor-pointer">
                                <img id="cover_preview" src="" alt="" class="w-full h-20 rounded mx-auto mb-2 object-cover hidden">
                                <div id="cover_placeholder" class="w-full h-20 rounded mx-auto mb-2 bg-gray-100 flex items-center justify-center text-gray-400 text-2xl">
                                    +
                                </div>
                                <p class="text-gray-500 text-xs">اضغط لرفع الغلاف</p>
                                <p class="text-gray-400 text-xs">PNG, JPG حتى 2MB</p>
                            </label>
                        </div>
                        @error('cover_image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">الشعار (Logo)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                            <input type="file" name="logo" accept="image/*" class="hidden" id="logo"
                                   onchange="previewImage(this, 'logo_preview')">
                            <label for="logo" class="cursor-pointer">
                                <img id="logo_preview" src="" alt="" class="w-24 h-24 mx-auto mb-2 object-contain hidden">
                                <div id="logo_placeholder" class="w-24 h-24 mx-auto mb-2 bg-gray-100 flex items-center justify-center text-gray-400 text-3xl rounded">
                                    +
                                </div>
                                <p class="text-gray-500 text-xs">اضغط لرفع الشعار</p>
                                <p class="text-gray-400 text-xs">PNG, JPG, SVG حتى 2MB</p>
                            </label>
                        </div>
                        @error('logo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Step 6: Settings -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">6</span>
                    إعدادات البطاقة
                </h2>

                <div class="flex flex-wrap gap-6 mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <div>
                            <p class="font-semibold text-sm">تفعيل البطاقة</p>
                            <p class="text-gray-500 text-xs">البطاقة ستكون قابلة للعرض</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <div>
                            <p class="font-semibold text-sm">نشر البطاقة</p>
                            <p class="text-gray-500 text-xs">ستكون مرئية للجميع عبر الرابط</p>
                        </div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">كلمة مرور البطاقة</label>
                        <input type="text" name="password" value="{{ old('password') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="اتركه فارغاً بدون حماية">
                        <p class="text-gray-400 text-xs mt-1">سيُطلب من الزائر إدخال كلمة المرور قبل عرض البطاقة</p>
                        @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">تاريخ انتهاء الصلاحية</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-gray-400 text-xs mt-1">اتركه فارغاً لبطاقة بدون تاريخ انتهاء</p>
                        @error('expires_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold transition shadow-sm">
                    إنشاء البطاقة
                </button>
                <a href="{{ route('customer.cards.index') }}" class="text-gray-600 hover:text-gray-800 px-6 py-3">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(previewId.replace('_preview', '_placeholder'));
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
