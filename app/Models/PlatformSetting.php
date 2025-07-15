<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'integer' => (int) $setting->value,
            'decimal' => (float) $setting->value,
            'boolean' => (bool) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    /**
     * Set a setting value.
     */
    public static function setValue(string $key, mixed $value, string $type = 'string', ?string $description = null): void
    {
        $setting = static::firstOrNew(['key' => $key]);

        $setting->value = match ($type) {
            'json' => json_encode($value),
            default => (string) $value,
        };

        $setting->type = $type;
        $setting->description = $description;
        $setting->save();
    }

    /**
     * Get the default maintenance fee percentage.
     */
    public static function getMaintenanceFeePercentage(): float
    {
        return static::getValue('maintenance_fee_percentage', 5.0);
    }

    /**
     * Set the default maintenance fee percentage.
     */
    public static function setMaintenanceFeePercentage(float $percentage): void
    {
        static::setValue('maintenance_fee_percentage', $percentage, 'decimal', 'Taxa de manutenção padrão da plataforma (%)');
    }

    /**
     * Get platform name.
     */
    public static function getPlatformName(): string
    {
        return static::getValue('platform_name', 'Passfy');
    }

    /**
     * Set platform name.
     */
    public static function setPlatformName(string $name): void
    {
        static::setValue('platform_name', $name, 'string', 'Nome da plataforma');
    }

    /**
     * Get platform description.
     */
    public static function getPlatformDescription(): string
    {
        return static::getValue('platform_description', 'Sua plataforma de eventos');
    }

    /**
     * Set platform description.
     */
    public static function setPlatformDescription(string $description): void
    {
        static::setValue('platform_description', $description, 'string', 'Descrição da plataforma');
    }
}
