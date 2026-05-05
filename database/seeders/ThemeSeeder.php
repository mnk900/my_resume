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
            ['name' => 'Modern Dark', 'slug' => 'modern', 'is_active' => true],
            ['name' => 'Elegant Indigo', 'slug' => 'elegant', 'is_active' => true],
            ['name' => 'Vibrant Gradient', 'slug' => 'vibrant', 'is_active' => true],
            ['name' => 'Glassmorphism Dark', 'slug' => 'glass', 'is_active' => true],
            ['name' => 'Premium Professional', 'slug' => 'premium', 'is_active' => true],
        ];

        foreach ($themes as $theme) {
            Theme::updateOrCreate(['slug' => $theme['slug']], $theme);
        }
    }
}
