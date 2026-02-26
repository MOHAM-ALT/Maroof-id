<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class UpdateTemplateDesignConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            1 => [ // احترافي أساسي - Professional Basic
                'colors' => [
                    'primary' => '#1E3A5F',
                    'secondary' => '#2563EB',
                    'accent' => '#F59E0B',
                    'background' => '#FFFFFF',
                    'text' => '#1F2937',
                ],
                'font' => 'Tajawal',
                'layout' => 'standard',
                'styles' => ['borderRadius' => '12px', 'shadow' => 'md', 'btnStyle' => 'rounded'],
            ],
            2 => [ // أنيق - Elegant
                'colors' => [
                    'primary' => '#B8860B',
                    'secondary' => '#D4A84B',
                    'accent' => '#8B6914',
                    'background' => '#FDF8F0',
                    'text' => '#3D2B1F',
                ],
                'font' => 'Playfair Display',
                'layout' => 'elegant',
                'styles' => ['borderRadius' => '8px', 'shadow' => 'lg', 'btnStyle' => 'pill'],
            ],
            3 => [ // عصري - Modern
                'colors' => [
                    'primary' => '#7C3AED',
                    'secondary' => '#EC4899',
                    'accent' => '#06B6D4',
                    'background' => '#FAF5FF',
                    'text' => '#1E1B4B',
                ],
                'font' => 'Cairo',
                'layout' => 'modern',
                'styles' => ['borderRadius' => '16px', 'shadow' => 'xl', 'btnStyle' => 'rounded'],
            ],
            4 => [ // كلاسيكي - Classic
                'colors' => [
                    'primary' => '#C8A951',
                    'secondary' => '#1A1A2E',
                    'accent' => '#E8D5A3',
                    'background' => '#0F0F1A',
                    'text' => '#F5F5F5',
                ],
                'font' => 'Amiri',
                'layout' => 'classic',
                'styles' => ['borderRadius' => '4px', 'shadow' => 'lg', 'btnStyle' => 'square'],
            ],
            5 => [ // بسيط - Minimal
                'colors' => [
                    'primary' => '#6B7280',
                    'secondary' => '#9CA3AF',
                    'accent' => '#374151',
                    'background' => '#FFFFFF',
                    'text' => '#111827',
                ],
                'font' => 'IBM Plex Sans Arabic',
                'layout' => 'minimal',
                'styles' => ['borderRadius' => '8px', 'shadow' => 'sm', 'btnStyle' => 'rounded'],
            ],
            6 => [ // جريء - Bold
                'colors' => [
                    'primary' => '#DC2626',
                    'secondary' => '#991B1B',
                    'accent' => '#FBBF24',
                    'background' => '#1C1917',
                    'text' => '#FAFAF9',
                ],
                'font' => 'Cairo',
                'layout' => 'bold',
                'styles' => ['borderRadius' => '0px', 'shadow' => 'xl', 'btnStyle' => 'square'],
            ],
            7 => [ // أنيق ذهبي - Golden Elegant
                'colors' => [
                    'primary' => '#A67C00',
                    'secondary' => '#C9A84C',
                    'accent' => '#FFF8DC',
                    'background' => '#FFFBEB',
                    'text' => '#422006',
                ],
                'font' => 'Noto Naskh Arabic',
                'layout' => 'elegant',
                'styles' => ['borderRadius' => '12px', 'shadow' => 'md', 'btnStyle' => 'pill'],
            ],
            8 => [ // تقني - Tech
                'colors' => [
                    'primary' => '#10B981',
                    'secondary' => '#059669',
                    'accent' => '#34D399',
                    'background' => '#0F172A',
                    'text' => '#E2E8F0',
                ],
                'font' => 'IBM Plex Sans Arabic',
                'layout' => 'modern',
                'styles' => ['borderRadius' => '8px', 'shadow' => 'lg', 'btnStyle' => 'rounded'],
            ],
            9 => [ // طبيعي - Natural
                'colors' => [
                    'primary' => '#16A34A',
                    'secondary' => '#4ADE80',
                    'accent' => '#A3E635',
                    'background' => '#F0FDF4',
                    'text' => '#14532D',
                ],
                'font' => 'Tajawal',
                'layout' => 'minimal',
                'styles' => ['borderRadius' => '16px', 'shadow' => 'md', 'btnStyle' => 'pill'],
            ],
            10 => [ // ملكي - Royal
                'colors' => [
                    'primary' => '#581C87',
                    'secondary' => '#7E22CE',
                    'accent' => '#D4AF37',
                    'background' => '#1E1033',
                    'text' => '#F3E8FF',
                ],
                'font' => 'Amiri',
                'layout' => 'classic',
                'styles' => ['borderRadius' => '8px', 'shadow' => 'xl', 'btnStyle' => 'rounded'],
            ],
            11 => [ // نابض - Vibrant
                'colors' => [
                    'primary' => '#EA580C',
                    'secondary' => '#F97316',
                    'accent' => '#EAB308',
                    'background' => '#FFFBEB',
                    'text' => '#431407',
                ],
                'font' => 'Cairo',
                'layout' => 'bold',
                'styles' => ['borderRadius' => '12px', 'shadow' => 'lg', 'btnStyle' => 'rounded'],
            ],
            12 => [ // داكن - Dark
                'colors' => [
                    'primary' => '#3B82F6',
                    'secondary' => '#1E40AF',
                    'accent' => '#60A5FA',
                    'background' => '#111827',
                    'text' => '#F9FAFB',
                ],
                'font' => 'Tajawal',
                'layout' => 'modern',
                'styles' => ['borderRadius' => '12px', 'shadow' => 'lg', 'btnStyle' => 'rounded'],
            ],
            13 => [ // فاتح - Light
                'colors' => [
                    'primary' => '#0EA5E9',
                    'secondary' => '#38BDF8',
                    'accent' => '#7DD3FC',
                    'background' => '#F0F9FF',
                    'text' => '#0C4A6E',
                ],
                'font' => 'Tajawal',
                'layout' => 'minimal',
                'styles' => ['borderRadius' => '16px', 'shadow' => 'sm', 'btnStyle' => 'pill'],
            ],
            14 => [ // لات - Latte
                'colors' => [
                    'primary' => '#BE185D',
                    'secondary' => '#EC4899',
                    'accent' => '#F9A8D4',
                    'background' => '#FFF1F2',
                    'text' => '#4C0519',
                ],
                'font' => 'Cairo',
                'layout' => 'elegant',
                'styles' => ['borderRadius' => '20px', 'shadow' => 'md', 'btnStyle' => 'pill'],
            ],
        ];

        foreach ($configs as $id => $config) {
            Template::where('id', $id)->update(['design_config' => json_encode($config)]);
        }

        $this->command->info('Updated design_config for ' . count($configs) . ' templates.');
    }
}
