@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">الملف الشخصي</h1>

    <div class="space-y-4">
        <div>
            <label class="block text-gray-600">الاسم</label>
            <p class="text-xl font-semibold">{{ auth()->user()->name }}</p>
        </div>
        <div>
            <label class="block text-gray-600">البريد الإلكتروني</label>
            <p class="text-xl font-semibold">{{ auth()->user()->email }}</p>
        </div>
        <div>
            <label class="block text-gray-600">رقم الهاتف</label>
            <p class="text-xl font-semibold">{{ auth()->user()->phone ?? 'لم يتم إدخاله' }}</p>
        </div>
        <div>
            <label class="block text-gray-600">تاريخ الانضمام</label>
            <p class="text-xl font-semibold">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('customer.profile.edit') }}" class="bg-blue-600 text-white px-6 py-3 rounded">تعديل الملف</a>
    </div>
</div>
@endsection
