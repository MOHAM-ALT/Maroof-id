<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateGalleryController extends Controller
{
    /**
     * Display all templates.
     */
    public function index(Request $request): View
    {
        $query = Template::active();

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('template_category_id', $request->category);
        }

        // Filter by price
        if ($request->has('price')) {
            if ($request->price === 'free') {
                $query->where('price', 0);
            } elseif ($request->price === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Search by name or description (supports both AR and EN)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('description_ar', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('usage_count', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default: // latest
                $query->latest();
                break;
        }

        $templates = $query->paginate(12)->withQueryString();

        return view('public.templates', compact('templates'));
    }

    /**
     * Display template details.
     */
    public function show(Template $template): View
    {
        if (!$template->is_active) {
            abort(404);
        }

        // Increment usage count
        $template->incrementUsage();

        // Get related templates from same category
        $relatedTemplates = Template::active()
            ->where('template_category_id', $template->template_category_id)
            ->where('id', '!=', $template->id)
            ->limit(4)
            ->get();

        return view('public.template-detail', compact('template', 'relatedTemplates'));
    }
}
