<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(): View
    {
        $user = auth()->user();

        return view('customer.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit(): View
    {
        $user = auth()->user();

        return view('customer.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotificationPreferences(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'in_app_notifications' => 'boolean',
        ]);

        auth()->user()->update([
            'email_notifications' => $validated['email_notifications'] ?? false,
            'sms_notifications' => $validated['sms_notifications'] ?? false,
            'in_app_notifications' => $validated['in_app_notifications'] ?? false,
        ]);

        return back()->with('success', 'تم تحديث تفضيلات الإشعارات');
    }
}
