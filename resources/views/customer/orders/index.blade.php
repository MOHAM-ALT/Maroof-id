@extends('layouts.app')

@section('title', 'الطلبات')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">الطلبات</h1>
    <a href="{{ route('customer.orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ طلب جديد</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-4 py-3 text-right">رقم الطلب</th>
                <th class="px-4 py-3 text-right">الحالة</th>
                <th class="px-4 py-3 text-right">الإجمالي</th>
                <th class="px-4 py-3 text-right">التاريخ</th>
                <th class="px-4 py-3 text-right">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">#{{ $order->id }}</td>
                    <td class="px-4 py-3"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $order->status }}</span></td>
                    <td class="px-4 py-3">{{ $order->total }} ر.س</td>
                    <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('customer.orders.show', $order) }}" class="text-blue-600">عرض</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد طلبات</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>
@endsection
