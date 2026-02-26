<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Str;

class CardService
{
    /**
     * Create a new card for a user
     *
     * @param User $user
     * @param array $data
     * @return Card
     */
    public function createCard(User $user, array $data): Card
    {
        $data['user_id'] = $user->id;
        $data['slug'] = $this->generateSlug($data['title'] ?? 'card');
        $data['nfc_id'] = $this->generateNfcId();
        $data['qr_code'] = $this->generateQrCode($data['nfc_id']);
        
        return Card::create($data);
    }

    /**
     * Update an existing card
     *
     * @param Card $card
     * @param array $data
     * @return Card
     */
    public function updateCard(Card $card, array $data): Card
    {
        if (isset($data['title']) && $data['title'] !== $card->title) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        $card->update($data);
        return $card->fresh();
    }

    /**
     * Publish a card (make it active and public)
     *
     * @param Card $card
     * @return Card
     */
    public function publishCard(Card $card): Card
    {
        return $this->updateCard($card, [
            'is_active' => true,
            'is_public' => true,
        ]);
    }

    /**
     * Archive a card (soft delete)
     *
     * @param Card $card
     * @return bool
     */
    public function archiveCard(Card $card): bool
    {
        return $card->delete();
    }

    /**
     * Get card analytics/statistics
     *
     * @param Card $card
     * @return array
     */
    public function getCardAnalytics(Card $card): array
    {
        return [
            'total_views' => $card->views()->count(),
            'unique_views' => $card->views()->distinct('ip_address')->count(),
            'social_clicks' => $card->socialLinks()->sum('clicks_count'),
            'created_at' => $card->created_at,
        ];
    }

    /**
     * Duplicate a card for a user
     *
     * @param Card $sourceCard
     * @param User $targetUser
     * @return Card
     */
    public function duplicateCard(Card $sourceCard, User $targetUser): Card
    {
        $cardData = $sourceCard->replicate()->makeHidden(['id', 'user_id', 'nfc_id', 'qr_code', 'created_at', 'updated_at', 'deleted_at'])->toArray();
        $cardData['title'] = $cardData['title'] . ' (Copy)';
        
        return $this->createCard($targetUser, $cardData);
    }

    /**
     * Generate a unique slug for a card
     *
     * @param string $title
     * @return string
     */
    private function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Card::where('slug', 'like', $slug . '%')->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Generate a unique NFC ID
     *
     * @return string
     */
    private function generateNfcId(): string
    {
        do {
            $nfcId = Str::random(16);
        } while (Card::where('nfc_id', $nfcId)->exists());
        
        return $nfcId;
    }

    /**
     * Generate a QR code URL/data for NFC ID
     *
     * @param string $nfcId
     * @return string
     */
    private function generateQrCode(string $nfcId): string
    {
        return url('/nfc/' . $nfcId);
    }
}
