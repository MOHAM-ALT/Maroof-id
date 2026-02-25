<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($validated, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Redirect based on role
            $user = Auth::user();
            $role = $user->roles->first()?->name ?? 'customer';
            
            return match($role) {
                'admin' => redirect('/admin'),
                'customer' => redirect('/customer/dashboard'),
                'partner' => redirect('/partner/dashboard'),
                'reseller' => redirect('/reseller/dashboard'),
                'designer' => redirect('/designer/dashboard'),
                'affiliate' => redirect('/affiliate/dashboard'),
                'business' => redirect('/business/dashboard'),
                default => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
