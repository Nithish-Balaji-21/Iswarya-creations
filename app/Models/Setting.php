<?php

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $record = static::where('key', $key)->first();

            return $record !== null ? $record->value : $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting_{$key}");
    }

    public static function whatsappNumber(): string
    {
        return static::get('whatsapp_number', config('iswarya.whatsapp_number', '918973948912'));
    }

    public static function storeAddress(): string
    {
        return static::get('store_address', config('iswarya.store_address'));
    }

    public static function instagramUrl(): string
    {
        return static::get('instagram_url', config('iswarya.instagram_url'));
    }

    public static function instagramHandle(): string
    {
        return static::get('instagram_handle', config('iswarya.instagram_handle', '@iswaryasilks.cbe'));
    }

    public static function googleMapsUrl(): string
    {
        return static::get('google_maps_url', config('iswarya.google_maps_url', 'https://share.google/qQvd7CVGrSjmYrHhM'));
    }

    public static function contactEmail(): string
    {
        return static::get('contact_email', config('iswarya.contact_email', 'iswaryacreations1975@gmail.com'));
    }

    public static function contactPhone(): string
    {
        return static::get('contact_phone', config('iswarya.contact_phone', '+91 89739 48912'));
    }

    public static function announcementText(): string
    {
        return static::get('announcement_text', config('iswarya.default_announcement'));
    }
}
