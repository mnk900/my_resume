<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            ['name' => 'Classic Clean', 'slug' => 'classic', 'is_active' => true],
            ['name' => 'Elegant Indigo', 'slug' => 'elegant', 'is_active' => true],
            ['name' => 'Premium Professional', 'slug' => 'premium', 'is_active' => true],
            ['name' => 'Executive Theme', 'slug' => 'executive', 'is_active' => true],
            ['name' => 'Business Class', 'slug' => 'business-class', 'is_active' => true],
        ];

        // Clean up deleted themes
        $slugs = array_column($themes, 'slug');
        Theme::whereNotIn('slug', $slugs)->delete();

        foreach ($themes as $theme) {
            Theme::updateOrCreate(['slug' => $theme['slug']], $theme);
        }
    }
}
