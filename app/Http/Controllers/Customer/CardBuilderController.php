<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Template;
use App\Services\TemplateRenderer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CardBuilderController extends Controller
{
    public function create(): View
    {
        $templates = Template::active()->get();
        return view('customer.builder.create', [
            'templates' => $templates,
            'card' => null,
        ]);
    }

    public function edit(Card $card): View
    {
        $this->authorize('update', $card);
        $card->load('socialLinks');
        $templates = Template::active()->get();

        return view('customer.builder.create', [
            'templates' => $templates,
            'card' => $card,
        ]);
    }

    public function save(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'card_id' => 'nullable|exists:cards,id',
            'title' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'template_id' => 'nullable|exists:templates,id',
            'design_data' => 'nullable|json',
            'social_links' => 'nullable|array',
            'is_public' => 'nullable|boolean',
            'password' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date',
            'slug' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();

        $cardData = [
            'title' => $validated['title'],
            'full_name' => $validated['full_name'],
            'job_title' => $validated['job_title'] ?? null,
            'company' => $validated['company'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'website' => $validated['website'] ?? null,
            'address' => $validated['address'] ?? null,
            'template_id' => $validated['template_id'] ?? null,
            'design_data' => $validated['design_data'] ?? null,
            'is_public' => $validated['is_public'] ?? false,
            'password' => !empty($validated['password']) ? $validated['password'] : null,
            'expires_at' => $validated['expires_at'] ?? null,
        ];

        if (!empty($validated['card_id'])) {
            $card = Card::findOrFail($validated['card_id']);
            $this->authorize('update', $card);

            // Update slug if provided and different
            if (!empty($validated['slug']) && $validated['slug'] !== $card->slug) {
                $slug = Str::slug($validated['slug']);
                if (!Card::where('slug', $slug)->where('id', '!=', $card->id)->exists()) {
                    $cardData['slug'] = $slug;
                }
            }

            $card->update($cardData);
        } else {
            $slug = !empty($validated['slug'])
                ? Str::slug($validated['slug'])
                : Str::slug($validated['full_name'] . '-' . Str::random(4));

            // Ensure unique slug
            while (Card::where('slug', $slug)->exists()) {
                $slug = Str::slug($validated['full_name'] . '-' . Str::random(4));
            }

            $cardData['slug'] = $slug;
            $cardData['is_active'] = true;
            $card = $user->cards()->create($cardData);
        }

        // Save social links
        if (isset($validated['social_links'])) {
            $card->socialLinks()->delete();
            $order = 0;
            foreach ($validated['social_links'] as $platform => $url) {
                if (!empty($url)) {
                    $card->socialLinks()->create([
                        'platform' => $platform,
                        'url' => $url,
                        'sort_order' => $order++,
                        'is_active' => true,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'slug' => $card->slug,
            'message' => 'تم حفظ البطاقة بنجاح',
            'url' => route('customer.cards.show', $card),
            'public_url' => route('public.cards.show', $card->slug),
        ]);
    }

    public function previewTemplate(Request $request): Response
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
        ]);

        $template = Template::findOrFail($request->template_id);

        if (!$template->html_content) {
            return response('<p style="text-align:center;padding:40px;color:#999;">لا يوجد معاينة HTML لهذا القالب</p>');
        }

        $renderer = app(TemplateRenderer::class);
        $html = $renderer->renderPreview($template->html_content, $request->all());

        return response($html);
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'type' => 'required|in:profile,cover,logo,element',
        ]);

        $path = $request->file('image')->store('cards/builder/' . $request->type, 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $path),
            'path' => $path,
        ]);
    }
}
