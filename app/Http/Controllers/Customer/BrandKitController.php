<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BrandKit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrandKitController extends Controller
{
    public function index(): View
    {
        $brandKits = auth()->user()->brandKits()->latest()->get();
        return view('customer.brand-kit.index', compact('brandKits'));
    }

    public function create(): View
    {
        return view('customer.brand-kit.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'accent_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'background_color' => 'required|string|max:7',
            'font_family' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'icon' => 'nullable|image|max:1024',
            'cover_image' => 'nullable|image|max:4096',
            'social_defaults' => 'nullable|array',
            'default_bio' => 'nullable|string|max:1000',
            'default_company' => 'nullable|string|max:255',
            'default_website' => 'nullable|url|max:255',
            'is_default' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('brand-kits/logos', 'public');
        }
        if ($request->hasFile('icon')) {
            $validated['icon_path'] = $request->file('icon')->store('brand-kits/icons', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image_path'] = $request->file('cover_image')->store('brand-kits/covers', 'public');
        }

        unset($validated['logo'], $validated['icon'], $validated['cover_image']);

        if (!empty($validated['is_default'])) {
            auth()->user()->brandKits()->update(['is_default' => false]);
        }

        auth()->user()->brandKits()->create($validated);

        return redirect()->route('customer.brand-kit.index')
            ->with('success', 'تم إنشاء هوية العلامة التجارية بنجاح');
    }

    public function edit(BrandKit $brandKit): View
    {
        $this->authorize('update', $brandKit);
        return view('customer.brand-kit.edit', compact('brandKit'));
    }

    public function update(Request $request, BrandKit $brandKit): RedirectResponse
    {
        $this->authorize('update', $brandKit);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'accent_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'background_color' => 'required|string|max:7',
            'font_family' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'icon' => 'nullable|image|max:1024',
            'cover_image' => 'nullable|image|max:4096',
            'social_defaults' => 'nullable|array',
            'default_bio' => 'nullable|string|max:1000',
            'default_company' => 'nullable|string|max:255',
            'default_website' => 'nullable|url|max:255',
            'is_default' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('brand-kits/logos', 'public');
        }
        if ($request->hasFile('icon')) {
            $validated['icon_path'] = $request->file('icon')->store('brand-kits/icons', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image_path'] = $request->file('cover_image')->store('brand-kits/covers', 'public');
        }

        unset($validated['logo'], $validated['icon'], $validated['cover_image']);

        if (!empty($validated['is_default'])) {
            auth()->user()->brandKits()->where('id', '!=', $brandKit->id)->update(['is_default' => false]);
        }

        $brandKit->update($validated);

        return redirect()->route('customer.brand-kit.index')
            ->with('success', 'تم تحديث هوية العلامة التجارية بنجاح');
    }

    public function destroy(BrandKit $brandKit): RedirectResponse
    {
        $this->authorize('delete', $brandKit);
        $brandKit->delete();

        return redirect()->route('customer.brand-kit.index')
            ->with('success', 'تم حذف هوية العلامة التجارية');
    }
}
