<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClickController extends Controller
{
    public function index(Request $request)
    {
        $affiliate = Auth::user()->affiliate;
        if (!$affiliate) {
            return redirect()->route('affiliate.dashboard');
        }

        $query = $affiliate->clicks();

        if ($request->filled('converted')) {
            $query->where('converted', $request->converted === 'yes');
        }

        $clicks = $query->latest('clicked_at')->paginate(20);
        $totalClicks = $affiliate->clicks()->count();
        $conversions = $affiliate->clicks()->where('converted', true)->count();

        return view('affiliate.clicks.index', compact('clicks', 'affiliate', 'totalClicks', 'conversions'));
    }
}
