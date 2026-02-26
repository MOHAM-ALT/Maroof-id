<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ImportHtmlTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $basePath = base_path('ai-workspace/templates/cards');

        // Map existing templates (IDs 1-6) to HTML files
        $existingMapping = [
            1 => 'professional.html',
            2 => 'luxury.html',
            3 => 'modern.html',
            4 => 'classic.html',
            5 => 'minimal.html',
            6 => 'friendly.html',
        ];

        foreach ($existingMapping as $id => $file) {
            $filePath = $basePath . '/' . $file;
            if (file_exists($filePath)) {
                Template::where('id', $id)->update([
                    'html_content' => file_get_contents($filePath),
                ]);
                $this->command->info("Updated template #{$id} with {$file}");
            } else {
                $this->command->warn("File not found: {$file}");
            }
        }

        // Import new templates (Gaming, Japan70s, Website, IT)
        $newTemplates = [
            [
                'file' => 'Gaming.html',
                'name_ar' => 'قيمنق',
                'name_en' => 'Gaming',
                'description_ar' => 'تصميم مخصص للاعبين ومحبي الألعاب',
                'description_en' => 'A design for gamers and gaming enthusiasts',
            ],
            [
                'file' => 'Japan70s.html',
                'name_ar' => 'ياباني كلاسيكي',
                'name_en' => 'Japan 70s',
                'description_ar' => 'تصميم مستوحى من الطراز الياباني السبعينات',
                'description_en' => 'A design inspired by 70s Japanese style',
            ],
            [
                'file' => 'Website.html',
                'name_ar' => 'موقع ويب',
                'name_en' => 'Website',
                'description_ar' => 'تصميم يشبه المواقع الإلكترونية الاحترافية',
                'description_en' => 'A professional website-style design',
            ],
            [
                'file' => 'it.html',
                'name_ar' => 'تقني ريترو',
                'name_en' => 'IT Retro',
                'description_ar' => 'تصميم كلاسيكي بطابع تقني',
                'description_en' => 'A retro tech-style design',
            ],
        ];

        $categoryId = TemplateCategory::where('is_active', true)->value('id') ?? 1;

        foreach ($newTemplates as $template) {
            $filePath = $basePath . '/' . $template['file'];
            if (!file_exists($filePath)) {
                $this->command->warn("File not found: {$template['file']}");
                continue;
            }

            $existing = Template::where('name_en', $template['name_en'])->first();
            if ($existing) {
                $existing->update(['html_content' => file_get_contents($filePath)]);
                $this->command->info("Updated existing template: {$template['name_en']}");
            } else {
                Template::create([
                    'template_category_id' => $categoryId,
                    'name_ar' => $template['name_ar'],
                    'name_en' => $template['name_en'],
                    'slug' => Str::slug($template['name_en']) . '-' . Str::random(5),
                    'description_ar' => $template['description_ar'],
                    'description_en' => $template['description_en'],
                    'html_content' => file_get_contents($filePath),
                    'design_config' => json_encode([
                        'colors' => ['primary' => '#333', 'secondary' => '#666', 'accent' => '#999', 'background' => '#fff', 'text' => '#000'],
                        'font' => 'Cairo',
                        'layout' => 'standard',
                        'styles' => ['borderRadius' => '12px', 'shadow' => 'md', 'btnStyle' => 'rounded'],
                    ]),
                    'price' => 0,
                    'is_premium' => false,
                    'is_active' => true,
                    'is_featured' => false,
                    'sort_order' => 100,
                ]);
                $this->command->info("Created new template: {$template['name_en']}");
            }
        }

        $this->command->info('HTML templates import completed.');
    }
}
