@extends('layouts.app')

@section('title', 'حساب المصمم - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
    <div class="max-w-md mx-auto text-center bg-white rounded-xl shadow-sm border p-8">
        <div class="text-5xl mb-4">🎨</div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">لم يتم ربط حسابك كمصمم</h2>
        <p class="text-gray-600 mb-6">يرجى التواصل مع الإدارة لتفعيل حسابك كمصمم قوالب.</p>
        <a href="{{ url('/') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-lg font-semibold transition inline-block">العودة للرئيسية</a>
    </div>
</div>
@endsection
