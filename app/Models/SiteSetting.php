<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'label',
    ];

    /**
     * Ambil value setting berdasarkan key, dengan caching.
     */
    public static function get(string $key, string $default = ''): string
    {
        return Cache::remember("site_setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? ($setting->value ?? $default) : $default;
        });
    }

    /**
     * Set value setting dan clear cache.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        Cache::forget("site_setting.{$key}");
    }

    /**
     * Ambil semua settings berdasarkan group.
     */
    public static function getByGroup(string $group)
    {
        return static::where('group', $group)->get();
    }
}
