<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $designer = $user->designer;

        if (!$designer) {
            return view('designer.no-profile');
        }

        $totalTemplates = $designer->templates()->count();
        $activeTemplates = $designer->templates()->where('is_active', true)->count();
        $totalUsage = $designer->templates()->sum('usage_count');
        $totalEarnings = $designer->earnings;
        $featuredCount = $designer->templates()->where('is_featured', true)->count();

        $recentTemplates = $designer->templates()
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        return view('designer.dashboard', compact(
            'designer', 'totalTemplates', 'activeTemplates', 'totalUsage',
            'totalEarnings', 'featuredCount', 'recentTemplates'
        ));
    }
}
