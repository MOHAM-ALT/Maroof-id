<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\User;
use App\Models\Order;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(): View
    {
        // Get featured templates
        $templates = Template::active()
            ->limit(6)
            ->get();

        // Get platform statistics
        $stats = [
            'total_users' => User::count(),
            'total_cards' => \App\Models\Card::count(),
            'total_orders' => Order::where('payment_status', PaymentStatus::Paid)->count(),
            'total_revenue' => Order::where('payment_status', PaymentStatus::Paid)->sum('total'),
        ];

        // Get testimonials (if you have a testimonials table)
        $testimonials = [];

        return view('public.home', compact('templates', 'stats', 'testimonials'));
    }

    /**
     * Display the about page.
     */
    public function about(): View
    {
        return view('public.about');
    }

    /**
     * Display the contact page.
     */
    public function contact(): View
    {
        return view('public.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // TODO: Send email or store in database
        // For now, just redirect back with success message

        return back()->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
    }
}
