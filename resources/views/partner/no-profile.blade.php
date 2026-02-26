@extends('layouts.app')

@section('title', 'معروف - شريك الطباعة')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 max-w-lg w-full text-center">
        <div class="w-20 h-20 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">لا يوجد ملف شريك</h2>
        <p class="text-gray-500 mb-8 leading-relaxed">
            لم يتم ربط حسابك بملف شريك طباعة. يرجى التواصل مع الإدارة.
        </p>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            العودة للرئيسية
        </a>
    </div>
</div>
@endsection
