<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = Auth::user();
        $roleName = $request->input('role');

        // Check if user has this role
        if ($user->hasRole($roleName)) {
            // Store the active role in session
            session(['active_role' => $roleName]);
            
            // Redirect based on role
            return $this->redirectByRole($roleName);
        }

        return back()->with('error', 'ليس لديك صلاحية لهذا الدور');
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'super_admin' => redirect('/admin'),
            'customer' => redirect('/customer/dashboard'),
            'print_partner' => redirect('/partner/dashboard'),
            'reseller' => redirect('/reseller/dashboard'),
            'designer' => redirect('/designer/dashboard'),
            'affiliate' => redirect('/affiliate/dashboard'),
            'business' => redirect('/business/dashboard'),
            default => redirect('/'),
        };
    }
}
