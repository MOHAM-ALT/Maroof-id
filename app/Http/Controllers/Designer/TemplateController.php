<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function index()
    {
        $designer = Auth::user()->designer;
        if (!$designer) {
            return redirect()->route('designer.dashboard');
        }

        $templates = $designer->templates()->with('category')->latest()->paginate(12);
        return view('designer.templates.index', compact('templates', 'designer'));
    }

    public function create()
    {
        $categories = TemplateCategory::where('is_active', true)->get();
        return view('designer.templates.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $designer = Auth::user()->designer;
        if (!$designer) {
            return redirect()->route('designer.dashboard');
        }

        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'template_category_id' => 'required|exists:template_categories,id',
            'description_ar' => 'nullable|string|max:1000',
            'description_en' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'is_premium' => 'boolean',
            'preview_image' => 'nullable|image|max:5120',
            'html_file' => 'required|file|mimes:html,htm|max:2048',
            'customizable_fields' => 'nullable|array',
            'customizable_fields.*' => 'string|in:primaryColor,secondaryColor,bgColor,textColor,font,borderRadius',
        ]);

        $validated['slug'] = Str::slug($validated['name_en']) . '-' . Str::random(5);
        $validated['designer_id'] = $designer->id;
        $validated['is_active'] = false;

        // Read HTML file content
        if ($request->hasFile('html_file')) {
            $validated['html_content'] = file_get_contents($request->file('html_file')->getRealPath());
        }
        unset($validated['html_file']);

        // Store customizable fields as JSON
        $validated['customizable_fields'] = $request->input('customizable_fields', []);

        if ($request->hasFile('preview_image')) {
            $validated['preview_image'] = $request->file('preview_image')->store('templates/previews', 'public');
        }

        Template::create($validated);
        $designer->increment('templates_count');

        return redirect()->route('designer.templates.index')->with('success', 'تم رفع القالب بنجاح. سيتم مراجعته من الإدارة.');
    }

    public function edit(Template $template)
    {
        $designer = Auth::user()->designer;
        if (!$designer || $template->designer_id !== $designer->id) {
            abort(403);
        }

        $categories = TemplateCategory::where('is_active', true)->get();
        return view('designer.templates.edit', compact('template', 'categories'));
    }

    public function update(Request $request, Template $template)
    {
        $designer = Auth::user()->designer;
        if (!$designer || $template->designer_id !== $designer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'template_category_id' => 'required|exists:template_categories,id',
            'description_ar' => 'nullable|string|max:1000',
            'description_en' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'is_premium' => 'boolean',
            'preview_image' => 'nullable|image|max:5120',
            'html_file' => 'nullable|file|mimes:html,htm|max:2048',
            'customizable_fields' => 'nullable|array',
            'customizable_fields.*' => 'string|in:primaryColor,secondaryColor,bgColor,textColor,font,borderRadius',
        ]);

        // Read HTML file content if uploaded
        if ($request->hasFile('html_file')) {
            $validated['html_content'] = file_get_contents($request->file('html_file')->getRealPath());
        }
        unset($validated['html_file']);

        // Store customizable fields
        $validated['customizable_fields'] = $request->input('customizable_fields', []);

        if ($request->hasFile('preview_image')) {
            $validated['preview_image'] = $request->file('preview_image')->store('templates/previews', 'public');
        }

        $template->update($validated);
        return redirect()->route('designer.templates.index')->with('success', 'تم تحديث القالب بنجاح');
    }
}
