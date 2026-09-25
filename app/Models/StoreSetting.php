<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $guarded = ['id'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, mixed $value, ?string $label = null, string $group = 'general'): self
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'label' => $label ?? ucwords(str_replace('_', ' ', $key)),
                'group' => $group,
            ]
        );
    }
}
