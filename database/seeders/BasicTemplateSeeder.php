<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Database\Seeder;

class BasicTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first category (Corporate)
        $category = TemplateCategory::where('slug', 'corporate-professional')->first();

        if (!$category) {
            $this->command->warn('⚠️ Template category not found. Run TemplateCategorySeeder first.');
            return;
        }

        $template = [
            'template_category_id' => $category->id,
            'name_ar' => 'قالب احترافي أساسي',
            'name_en' => 'Basic Professional Template',
            'slug' => 'basic-professional',
            'description_ar' => 'قالب بسيط وأنيق للاستخدام الاحترافي',
            'description_en' => 'Simple and elegant template for professional use',
            'preview_image' => null,
            'design_config' => [
                'colors' => [
                    'primary' => '#1a365d',
                    'secondary' => '#2b6cb0',
                    'accent' => '#ed8936',
                    'background' => '#ffffff',
                    'text' => '#1a202c',
                ],
                'fonts' => [
                    'heading' => 'IBM Plex Sans Arabic',
                    'body' => 'IBM Plex Sans Arabic',
                ],
                'layout' => 'centered',
            ],
            'price' => 0.00,
            'is_premium' => false,
            'is_active' => true,
            'is_featured' => true,
            'usage_count' => 0,
            'sort_order' => 1,
        ];

        Template::firstOrCreate(
            ['slug' => $template['slug']],
            $template
        );

        $this->command->info('✅ Created 1 basic template successfully!');
    }
}
