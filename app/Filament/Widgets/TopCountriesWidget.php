<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class TopCountriesWidget extends Widget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.top-countries-map';

    // Map Arabic country names to ISO 2-letter codes
    protected array $countryCodeMap = [
        'السعودية' => 'SA',
        'الإمارات' => 'AE',
        'الكويت' => 'KW',
        'البحرين' => 'BH',
        'قطر' => 'QA',
        'عمان' => 'OM',
        'مصر' => 'EG',
        'الأردن' => 'JO',
        'أمريكا' => 'US',
        'بريطانيا' => 'GB',
        'ألمانيا' => 'DE',
        'تركيا' => 'TR',
        'الهند' => 'IN',
        'باكستان' => 'PK',
        'العراق' => 'IQ',
        'سوريا' => 'SY',
        'لبنان' => 'LB',
        'فلسطين' => 'PS',
        'ليبيا' => 'LY',
        'تونس' => 'TN',
        'الجزائر' => 'DZ',
        'المغرب' => 'MA',
        'السودان' => 'SD',
        'اليمن' => 'YE',
        'فرنسا' => 'FR',
        'كندا' => 'CA',
        'أستراليا' => 'AU',
        'الصين' => 'CN',
        'اليابان' => 'JP',
        'كوريا' => 'KR',
        'ماليزيا' => 'MY',
        'إندونيسيا' => 'ID',
    ];

    public function getMapData(): array
    {
        $data = DB::table('card_views')
            ->select('country', DB::raw('COUNT(*) as total'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get();

        $mapValues = [];
        $tableData = [];

        foreach ($data as $row) {
            $code = $this->countryCodeMap[$row->country] ?? null;
            if ($code) {
                $mapValues[$code] = $row->total;
            }
            $tableData[] = [
                'country' => $row->country,
                'code' => $code,
                'total' => $row->total,
            ];
        }

        return [
            'mapValues' => $mapValues,
            'tableData' => array_slice($tableData, 0, 10),
            'totalViews' => $data->sum('total'),
        ];
    }
}
