<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    public static function get(string $key, $default = null)
    {
        return Cache::remember("system_setting_{$key}", 3600, function () use ($key, $default) {
            try {
                $setting = static::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            } catch (\Exception $e) {
                return $default;
            }
        });
    }

    public static function set(string $key, $value, ?string $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'description' => $description]
        );
        Cache::forget("system_setting_{$key}");
        return $setting;
    }

    public static function isAiMockEnabled(): bool
    {
        return static::get('ai_mock_interview_enabled', '1') === '1';
    }
}
