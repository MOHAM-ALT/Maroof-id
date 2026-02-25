<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Services\CardService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CardController extends Controller
{
    protected CardService $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    /**
     * GET /api/v1/cards
     * List all cards (paginated)
     */
    public function index(): JsonResponse
    {
        $cards = Card::query()
            ->where('is_public', true)
            ->with('user', 'template')
            ->paginate(15);

        return response()->json([
            'message' => 'تم جلب البطاقات بنجاح',
            'data' => $cards,
            'status' => 'success'
        ], 200);
    }

    /**
     * GET /api/v1/cards/{id}
     * Show single card
     */
    public function show(Card $card): JsonResponse
    {
        if (!$card->is_public && $card->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول',
                'status' => 'error'
            ], 403);
        }

        $card->increment('views_count');

        return response()->json([
            'message' => 'تم جلب البطاقة بنجاح',
            'data' => $card->load('user', 'template', 'socialLinks'),
            'status' => 'success'
        ], 200);
    }

    /**
     * POST /api/v1/cards
     * Create new card
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'template_id' => 'required|exists:templates,id',
        ]);

        try {
            $card = $this->cardService->createCard(auth()->user(), $validated);

            return response()->json([
                'message' => 'تم إنشاء البطاقة بنجاح',
                'data' => $card,
                'status' => 'success'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل إنشاء البطاقة: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * PUT /api/v1/cards/{id}
     * Update card
     */
    public function update(Request $request, Card $card): JsonResponse
    {
        if ($card->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالتعديل',
                'status' => 'error'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'full_name' => 'string|max:255',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        try {
            $updated = $this->cardService->updateCard($card, $validated);

            return response()->json([
                'message' => 'تم تحديث البطاقة بنجاح',
                'data' => $updated,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل التحديث: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * DELETE /api/v1/cards/{id}
     * Delete card
     */
    public function destroy(Card $card): JsonResponse
    {
        if ($card->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالحذف',
                'status' => 'error'
            ], 403);
        }

        try {
            $this->cardService->archiveCard($card);

            return response()->json([
                'message' => 'تم حذف البطاقة بنجاح',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل الحذف: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * GET /api/v1/cards/{id}/analytics
     * Get card analytics
     */
    public function analytics(Card $card): JsonResponse
    {
        if ($card->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول',
                'status' => 'error'
            ], 403);
        }

        $analytics = $this->cardService->getCardAnalytics($card);

        return response()->json([
            'message' => 'تم جلب الإحصائيات بنجاح',
            'data' => $analytics,
            'status' => 'success'
        ], 200);
    }

    /**
     * POST /api/v1/cards/{id}/publish
     * Publish card
     */
    public function publish(Card $card): JsonResponse
    {
        if ($card->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالنشر',
                'status' => 'error'
            ], 403);
        }

        try {
            $this->cardService->publishCard($card);

            return response()->json([
                'message' => 'تم نشر البطاقة بنجاح',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل النشر: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * GET /api/v1/my-cards
     * Get current user's cards
     */
    public function myCards(): JsonResponse
    {
        $cards = auth()->user()->cards()
            ->with('template')
            ->paginate(15);

        return response()->json([
            'message' => 'تم جلب بطاقاتك بنجاح',
            'data' => $cards,
            'status' => 'success'
        ], 200);
    }
}
