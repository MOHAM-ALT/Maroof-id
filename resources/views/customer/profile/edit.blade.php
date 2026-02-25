@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">تعديل الملف الشخصي</h1>
                <p class="text-gray-600 mt-1">تحديث معلوماتك الشخصية وكلمة المرور</p>
            </div>
            <a href="{{ route('customer.profile.show') }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; عرض الملف</a>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Profile Form -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">المعلومات الشخصية</h2>
            <form action="{{ route('customer.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+966 5X XXX XXXX"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" dir="ltr">
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">نبذة عنك</label>
                        <textarea name="bio" id="bio" rows="3" maxlength="500" placeholder="اكتب نبذة قصيرة عن نفسك..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">500 حرف كحد أقصى</p>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Form -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">تغيير كلمة المرور</h2>
            <form action="{{ route('customer.profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الحالية</label>
                        <input type="password" name="current_password" id="current_password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الجديدة</label>
                        <input type="password" name="password" id="password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <p class="text-xs text-gray-400 mt-1">8 أحرف على الأقل</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-gray-800 text-white px-6 py-3 rounded-lg hover:bg-gray-900 transition font-medium">
                        تغيير كلمة المرور
                    </button>
                </div>
            </form>
        </div>

        <!-- Notification Preferences -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">تفضيلات الإشعارات</h2>
            <form action="{{ route('customer.profile.notification-preferences') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <label class="flex items-center justify-between p-4 bg-gray-50 rounded-lg cursor-pointer">
                        <div>
                            <p class="font-medium text-gray-900">إشعارات البريد الإلكتروني</p>
                            <p class="text-sm text-gray-500">استلام تحديثات عبر البريد</p>
                        </div>
                        <input type="hidden" name="email_notifications" value="0">
                        <input type="checkbox" name="email_notifications" value="1"
                               {{ $user->email_notifications ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                    </label>

                    <label class="flex items-center justify-between p-4 bg-gray-50 rounded-lg cursor-pointer">
                        <div>
                            <p class="font-medium text-gray-900">إشعارات الرسائل النصية</p>
                            <p class="text-sm text-gray-500">استلام تحديثات عبر SMS</p>
                        </div>
                        <input type="hidden" name="sms_notifications" value="0">
                        <input type="checkbox" name="sms_notifications" value="1"
                               {{ $user->sms_notifications ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                    </label>

                    <label class="flex items-center justify-between p-4 bg-gray-50 rounded-lg cursor-pointer">
                        <div>
                            <p class="font-medium text-gray-900">إشعارات التطبيق</p>
                            <p class="text-sm text-gray-500">استلام إشعارات داخل المنصة</p>
                        </div>
                        <input type="hidden" name="in_app_notifications" value="0">
                        <input type="checkbox" name="in_app_notifications" value="1"
                               {{ $user->in_app_notifications ?? true ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                    </label>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-medium">
                        حفظ التفضيلات
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Info -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>تاريخ الانضمام: {{ $user->created_at->format('d/m/Y') }}</p>
        </div>
    </div>
</div>
@endsection
