<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Services\CardService;
use App\Services\TemplateRenderer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CardViewController extends Controller
{
    public function __construct(
        private CardService $cardService,
        private TemplateRenderer $templateRenderer,
    ) {}

    public function show(string $slug, Request $request): View|Response
    {
        $card = Card::where('slug', $slug)
            ->where('is_active', true)
            ->where('is_public', true)
            ->with(['user', 'template', 'activeSocialLinks'])
            ->firstOrFail();

        // Check if card is expired
        if ($card->isExpired()) {
            return view('public.card-expired', compact('card'));
        }

        // Check if card is password protected
        if ($card->isPasswordProtected()) {
            $sessionKey = 'card_unlocked_' . $card->id;
            if (!session($sessionKey)) {
                return view('public.card-password', compact('card'));
            }
        }

        // Track view
        $card->recordView([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'device_type' => $this->detectDeviceType($request->userAgent()),
            'browser' => $this->detectBrowser($request->userAgent()),
            'country' => null,
        ]);

        // If template has HTML content, render it directly
        if ($card->template && $card->template->html_content) {
            $html = $this->templateRenderer->render($card->template->html_content, $card);
            return response($html);
        }

        // Fallback to Blade view
        $analytics = $this->cardService->getCardAnalytics($card);

        return view('public.card', compact('card', 'analytics'));
    }

    public function unlock(string $slug, Request $request)
    {
        $request->validate(['password' => 'required|string']);

        $card = Card::where('slug', $slug)
            ->where('is_active', true)
            ->where('is_public', true)
            ->firstOrFail();

        if ($card->checkPassword($request->password)) {
            session(['card_unlocked_' . $card->id => true]);
            return redirect()->route('public.cards.show', $slug);
        }

        return back()->withErrors(['password' => 'كلمة المرور غير صحيحة']);
    }

    public function downloadVCard(string $slug)
    {
        $card = Card::where('slug', $slug)
            ->where('is_active', true)
            ->where('is_public', true)
            ->with('socialLinks')
            ->firstOrFail();

        $vcard = $this->generateVCard($card);

        return response($vcard)
            ->header('Content-Type', 'text/vcard; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename={$card->slug}.vcf");
    }

    private function generateVCard(Card $card): string
    {
        $name = $card->full_name ?? $card->title;
        $nameParts = explode(' ', $name, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= "FN:{$name}\r\n";
        $vcard .= "N:{$lastName};{$firstName};;;\r\n";

        if ($card->job_title) {
            $vcard .= "TITLE:{$card->job_title}\r\n";
        }
        if ($card->company) {
            $vcard .= "ORG:{$card->company}\r\n";
        }
        if ($card->phone) {
            $vcard .= "TEL;TYPE=CELL:{$card->phone}\r\n";
        }
        if ($card->whatsapp && $card->whatsapp !== $card->phone) {
            $vcard .= "TEL;TYPE=VOICE:{$card->whatsapp}\r\n";
        }
        if ($card->email) {
            $vcard .= "EMAIL:{$card->email}\r\n";
        }
        if ($card->website) {
            $vcard .= "URL:{$card->website}\r\n";
        }
        if ($card->address) {
            $vcard .= "ADR;TYPE=WORK:;;{$card->address};;;;\r\n";
        }
        if ($card->bio) {
            $vcard .= "NOTE:{$card->bio}\r\n";
        }

        $vcard .= "URL;TYPE=CARD:" . route('public.cards.show', $card->slug) . "\r\n";
        $vcard .= "END:VCARD\r\n";

        return $vcard;
    }

    private function detectDeviceType(?string $userAgent): string
    {
        if (!$userAgent) return 'unknown';
        if (preg_match('/mobile|android|iphone|ipod/i', $userAgent)) return 'mobile';
        if (preg_match('/tablet|ipad/i', $userAgent)) return 'tablet';
        return 'desktop';
    }

    private function detectBrowser(?string $userAgent): string
    {
        if (!$userAgent) return 'unknown';
        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) return 'Chrome';
        if (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) return 'Safari';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Edg')) return 'Edge';
        if (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) return 'Opera';
        return 'Other';
    }
}
