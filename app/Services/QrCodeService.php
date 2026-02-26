<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    /**
     * Generate QR code for card URL
     *
     * @param string $url The URL to encode in the QR code
     * @return string Base64 encoded data URI
     */
    public function generate(string $url): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(config('qr-code.card_qr.size'))
            ->margin(config('qr-code.card_qr.margin'))
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        return $result->getDataUri();
    }

    /**
     * Save QR code to storage
     *
     * @param string $url The URL to encode
     * @param string $path The storage path
     * @return string The saved file path
     */
    public function save(string $url, string $path): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(config('qr-code.card_qr.size'))
            ->margin(config('qr-code.card_qr.margin'))
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        $result->saveToFile(storage_path('app/public/' . $path));

        return $path;
    }
}
