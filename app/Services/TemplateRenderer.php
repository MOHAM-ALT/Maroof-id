<?php

namespace App\Services;

use App\Models\Card;

class TemplateRenderer
{
    /**
     * Render an HTML template with card data.
     */
    public function render(string $html, Card $card): string
    {
        $variables = $this->buildVariables($card);
        $html = $this->processConditionals($html, $variables);
        $html = $this->replaceVariables($html, $variables);

        return $html;
    }

    /**
     * Render a preview with raw data (for Builder Studio).
     */
    public function renderPreview(string $html, array $data): string
    {
        $variables = $this->buildPreviewVariables($data);
        $html = $this->processConditionals($html, $variables);
        $html = $this->replaceVariables($html, $variables);

        return $html;
    }

    /**
     * Build the variables map from a Card model.
     */
    protected function buildVariables(Card $card): array
    {
        $socialLinks = $card->relationLoaded('activeSocialLinks')
            ? $card->activeSocialLinks
            : $card->activeSocialLinks()->get();

        $getSocial = function (string $platform) use ($socialLinks): ?string {
            $link = $socialLinks->firstWhere('platform', $platform);
            return $link?->url;
        };

        $name = $card->full_name ?? $card->title ?? '';

        return [
            'NAME'         => $name,
            'NAME_INITIAL' => mb_substr($name, 0, 1),
            'JOB_TITLE'    => $card->job_title,
            'COMPANY'      => $card->company,
            'BIO'          => $card->bio,
            'PHONE'        => $card->phone,
            'PHONE_INTL'   => $this->formatPhoneIntl($card->phone),
            'WHATSAPP'     => $card->whatsapp,
            'EMAIL'        => $card->email,
            'WEBSITE'      => $card->website,
            'ADDRESS'      => $card->address,
            'PHOTO'        => $card->profile_image ? asset('storage/' . $card->profile_image) : null,
            'COVER'        => $card->cover_image ? asset('storage/' . $card->cover_image) : null,
            'LOGO'         => $card->logo ? asset('storage/' . $card->logo) : null,
            'INSTAGRAM'    => $getSocial('instagram'),
            'LINKEDIN'     => $getSocial('linkedin'),
            'TWITTER'      => $getSocial('twitter'),
            'YOUTUBE'      => $getSocial('youtube'),
            'TIKTOK'       => $getSocial('tiktok'),
            'SNAPCHAT'     => $getSocial('snapchat'),
            'GITHUB'       => $getSocial('github'),
            'CUSTOM_LINK'  => $getSocial('custom') ?? $getSocial('other'),
            'CV'           => $getSocial('cv') ?? $getSocial('resume'),
            'VCARD_URL'    => route('public.cards.download-vcard', $card->slug),
            'SHARE_URL'    => route('public.cards.show', $card->slug),
            'CARD_URL'     => route('public.cards.show', $card->slug),
        ];
    }

    /**
     * Build variables from raw preview data (Builder Studio).
     */
    protected function buildPreviewVariables(array $data): array
    {
        return [
            'NAME'         => $data['full_name'] ?? $data['name'] ?? 'الاسم الكامل',
            'NAME_INITIAL' => mb_substr($data['full_name'] ?? $data['name'] ?? 'م', 0, 1),
            'JOB_TITLE'    => $data['job_title'] ?? 'المسمى الوظيفي',
            'COMPANY'      => $data['company'] ?? 'الشركة',
            'BIO'          => $data['bio'] ?? null,
            'PHONE'        => $data['phone'] ?? '+966500000000',
            'PHONE_INTL'   => $this->formatPhoneIntl($data['phone'] ?? '+966500000000'),
            'WHATSAPP'     => $data['whatsapp'] ?? $data['phone'] ?? null,
            'EMAIL'        => $data['email'] ?? 'email@example.com',
            'WEBSITE'      => $data['website'] ?? null,
            'ADDRESS'      => $data['address'] ?? null,
            'PHOTO'        => $data['profile_image'] ?? null,
            'COVER'        => $data['cover_image'] ?? null,
            'LOGO'         => $data['logo'] ?? null,
            'INSTAGRAM'    => $data['instagram'] ?? '#',
            'LINKEDIN'     => $data['linkedin'] ?? '#',
            'TWITTER'      => $data['twitter'] ?? '#',
            'YOUTUBE'      => $data['youtube'] ?? null,
            'TIKTOK'       => $data['tiktok'] ?? null,
            'SNAPCHAT'     => $data['snapchat'] ?? null,
            'GITHUB'       => $data['github'] ?? null,
            'CUSTOM_LINK'  => $data['custom_link'] ?? null,
            'CV'           => $data['cv'] ?? null,
            'VCARD_URL'    => '#',
            'SHARE_URL'    => '#',
            'CARD_URL'     => '#',
        ];
    }

    /**
     * Process {{#if VARIABLE}}...{{/if}} and {{#if !VARIABLE}}...{{/if}} conditionals.
     * Also handles {{else}} blocks.
     */
    protected function processConditionals(string $html, array $variables): string
    {
        // Process {{#if VARIABLE}}...{{else}}...{{/if}} (with else block)
        $html = preg_replace_callback(
            '/\{\{#if\s+(!?)(\w+)\}\}(.*?)\{\{else\}\}(.*?)\{\{\/if\}\}/s',
            function ($matches) use ($variables) {
                $negated = $matches[1] === '!';
                $varName = $matches[2];
                $ifContent = $matches[3];
                $elseContent = $matches[4];

                $value = $variables[$varName] ?? null;
                $isTruthy = !empty($value);

                if ($negated) {
                    $isTruthy = !$isTruthy;
                }

                return $isTruthy ? $ifContent : $elseContent;
            },
            $html
        );

        // Process {{#if VARIABLE}}...{{/if}} (without else, may be nested)
        // Use recursive pattern to handle nested #if blocks
        $maxIterations = 20;
        $iteration = 0;
        while ($iteration < $maxIterations && preg_match('/\{\{#if\s+(!?\w+)\}\}/', $html)) {
            $html = preg_replace_callback(
                '/\{\{#if\s+(!?)(\w+)\}\}((?:(?!\{\{#if)(?!\{\{\/if\}\}).)*?)\{\{\/if\}\}/s',
                function ($matches) use ($variables) {
                    $negated = $matches[1] === '!';
                    $varName = $matches[2];
                    $content = $matches[3];

                    $value = $variables[$varName] ?? null;
                    $isTruthy = !empty($value);

                    if ($negated) {
                        $isTruthy = !$isTruthy;
                    }

                    return $isTruthy ? $content : '';
                },
                $html
            );
            $iteration++;
        }

        return $html;
    }

    /**
     * Replace {{VARIABLE}} placeholders with actual values.
     */
    protected function replaceVariables(string $html, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $html = str_replace('{{' . $key . '}}', e($value ?? ''), $html);
        }

        // Clean up any remaining unreplaced variables
        $html = preg_replace('/\{\{[A-Z_]+\}\}/', '', $html);

        return $html;
    }

    /**
     * Format phone number to international format.
     */
    protected function formatPhoneIntl(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        // Remove spaces, dashes, parentheses
        $clean = preg_replace('/[\s\-\(\)]/', '', $phone);

        // If starts with 0, assume Saudi (+966)
        if (str_starts_with($clean, '05')) {
            return '+966' . substr($clean, 1);
        }

        // If already has + prefix, return as-is
        if (str_starts_with($clean, '+')) {
            return $clean;
        }

        return $phone;
    }
}
