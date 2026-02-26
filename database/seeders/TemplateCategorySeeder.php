<?php

namespace Database\Seeders;

use App\Models\TemplateCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TemplateCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_ar' => 'شركات / احترافي',
                'name_en' => 'Corporate / Professional',
                'slug' => 'corporate-professional',
                'description_ar' => 'قوالب احترافية مناسبة للشركات والأعمال',
                'description_en' => 'Professional templates suitable for companies and businesses',
                'icon' => 'heroicon-o-building-office',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name_ar' => 'إبداعي / فني',
                'name_en' => 'Creative / Artistic',
                'slug' => 'creative-artistic',
                'description_ar' => 'قوالب إبداعية للفنانين والمصممين',
                'description_en' => 'Creative templates for artists and designers',
                'icon' => 'heroicon-o-paint-brush',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name_ar' => 'طبي / صحي',
                'name_en' => 'Medical / Healthcare',
                'slug' => 'medical-healthcare',
                'description_ar' => 'قوالب للأطباء والمهنيين الصحيين',
                'description_en' => 'Templates for doctors and healthcare professionals',
                'icon' => 'heroicon-o-heart',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            TemplateCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ Created 3 template categories successfully!');
    }
}
