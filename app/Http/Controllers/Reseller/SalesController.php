<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\ResellerSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $reseller = Auth::user()->reseller;
        if (!$reseller) return redirect()->route('reseller.dashboard');

        $query = $reseller->sales();

        if ($request->filled('month')) {
            $month = (int) $request->month;
            $year = now()->year;
            $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
            $query->whereBetween('sale_date', [$startDate, $endDate]);
        }

        $sales = $query->latest('sale_date')->paginate(15);
        $totalAmount = $reseller->sales()->sum('amount');
        $totalCommission = $reseller->sales()->sum('commission_earned');

        return view('reseller.sales.index', compact('sales', 'reseller', 'totalAmount', 'totalCommission'));
    }

    public function store(Request $request)
    {
        $reseller = Auth::user()->reseller;
        if (!$reseller) return redirect()->route('reseller.dashboard');

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
        ]);

        $validated['commission_earned'] = $validated['amount'] * ($reseller->commission_rate / 100);
        $validated['sale_date'] = now();

        $reseller->sales()->create($validated);

        // Decrease inventory
        $inventory = $reseller->inventory()->first();
        if ($inventory) {
            $inventory->decrement('card_quantity', $validated['quantity']);
        }

        return back()->with('success', 'تم تسجيل البيع بنجاح');
    }
}
