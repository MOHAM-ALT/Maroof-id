<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Template;
use App\Services\CardService;
use App\Http\Requests\Customer\StoreCardRequest;
use App\Http\Requests\Customer\UpdateCardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CardController extends Controller
{
    public function __construct(private CardService $cardService)
    {
    }

    public function index(): View
    {
        $cards = auth()->user()
            ->cards()
            ->with('template')
            ->latest()
            ->paginate(10);

        return view('customer.cards.index', compact('cards'));
    }

    public function create(): View
    {
        $templates = Template::active()->get();

        return view('customer.cards.create', compact('templates'));
    }

    public function store(StoreCardRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated = $this->handleImageUploads($request, $validated);

        $socialLinks = $validated['social_links'] ?? [];
        unset($validated['social_links']);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_public'] = $request->has('is_public');
        $validated['password'] = $request->filled('password') ? Hash::make($request->password) : null;
        $validated['expires_at'] = $request->input('expires_at') ?: null;

        $card = $this->cardService->createCard(auth()->user(), $validated);

        $this->saveSocialLinks($card, $socialLinks);

        return redirect()
            ->route('customer.cards.show', $card)
            ->with('success', 'تم إنشاء البطاقة بنجاح');
    }

    public function show(Card $card): View
    {
        $this->authorize('view', $card);

        $card->load(['template', 'socialLinks']);
        $analytics = $this->cardService->getCardAnalytics($card);

        return view('customer.cards.show', compact('card', 'analytics'));
    }

    public function edit(Card $card): View
    {
        $this->authorize('update', $card);

        $card->load('socialLinks');
        $templates = Template::active()->get();

        return view('customer.cards.edit', compact('card', 'templates'));
    }

    public function update(UpdateCardRequest $request, Card $card): RedirectResponse
    {
        $this->authorize('update', $card);

        $validated = $request->validated();

        $validated = $this->handleImageUploads($request, $validated);

        $socialLinks = $validated['social_links'] ?? [];
        unset($validated['social_links']);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_public'] = $request->has('is_public');

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else if ($request->has('remove_password')) {
            $validated['password'] = null;
        } else {
            unset($validated['password']);
        }

        $validated['expires_at'] = $request->input('expires_at') ?: null;

        $card = $this->cardService->updateCard($card, $validated);

        $this->saveSocialLinks($card, $socialLinks);

        return redirect()
            ->route('customer.cards.show', $card)
            ->with('success', 'تم تحديث البطاقة بنجاح');
    }

    public function destroy(Card $card): RedirectResponse
    {
        $this->authorize('delete', $card);

        $this->cardService->archiveCard($card);

        return redirect()
            ->route('customer.cards.index')
            ->with('success', 'تم حذف البطاقة بنجاح');
    }

    public function togglePublish(Card $card): RedirectResponse
    {
        $this->authorize('update', $card);

        if ($card->is_public) {
            $card->update(['is_public' => false]);
            $message = 'تم إلغاء نشر البطاقة';
        } else {
            $card->update(['is_public' => true, 'is_active' => true]);
            $message = 'تم نشر البطاقة';
        }

        return back()->with('success', $message);
    }

    public function downloadQR(Card $card)
    {
        $this->authorize('view', $card);

        $qrUrl = route('public.cards.show', $card->slug);
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode($qrUrl);
        $qrData = @file_get_contents($qrImageUrl);

        if (!$qrData) {
            return back()->with('error', 'فشل في إنشاء QR Code');
        }

        return response($qrData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', "attachment; filename=card-{$card->slug}-qr.png");
    }

    /**
     * Duplicate a card.
     */
    public function duplicate(Card $card): RedirectResponse
    {
        $this->authorize('view', $card);

        $newCard = $card->replicate(['slug', 'views_count']);
        $newCard->title = $card->title . ' (نسخة)';
        $newCard->full_name = ($card->full_name ?? $card->title) . ' (نسخة)';
        $newCard->slug = Str::slug($card->slug . '-copy-' . Str::random(4));
        $newCard->views_count = 0;
        $newCard->is_active = false;
        $newCard->save();

        // Duplicate social links
        foreach ($card->socialLinks as $link) {
            $newLink = $link->replicate(['clicks_count']);
            $newLink->card_id = $newCard->id;
            $newLink->clicks_count = 0;
            $newLink->save();
        }

        return redirect()
            ->route('customer.builder.edit', $newCard)
            ->with('success', 'تم نسخ البطاقة بنجاح. يمكنك تعديلها الآن.');
    }

    /**
     * Get share/embed data for a card.
     */
    public function share(Card $card): View
    {
        $this->authorize('view', $card);

        $cardUrl = route('public.cards.show', $card->slug);
        $embedCode = '<iframe src="' . $cardUrl . '?embed=1" width="400" height="700" frameborder="0" style="border-radius:12px;"></iframe>';

        return view('customer.cards.share', compact('card', 'cardUrl', 'embedCode'));
    }

    private function handleImageUploads(Request $request, array $validated): array
    {
        foreach (['profile_image', 'cover_image', 'logo'] as $imageField) {
            if ($request->hasFile($imageField)) {
                $validated[$imageField] = $request->file($imageField)->store('cards/' . $imageField . 's', 'public');
            } else {
                unset($validated[$imageField]);
            }
        }

        return $validated;
    }

    private function saveSocialLinks(Card $card, array $socialLinks): void
    {
        $socialLinks = array_filter($socialLinks);
        $platforms = array_keys($socialLinks);

        // Delete links that are no longer in the request to clean up
        $card->socialLinks()->whereNotIn('platform', $platforms)->delete();

        $order = 0;
        foreach ($socialLinks as $platform => $url) {
            if (!empty($url)) {
                $card->socialLinks()->updateOrCreate(
                    ['platform' => $platform],
                    [
                        'url' => $url,
                        'sort_order' => $order++,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
