<?php

namespace App\Models\Eloquent;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class JsonValueCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        if ($value == null) {
            return $value;
        }

        $decoded = json_decode($value, true);
        if ($decoded === NULL) {
            return $value;
        }

        return $decoded;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param array|string $value
     * @param array $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }
}
