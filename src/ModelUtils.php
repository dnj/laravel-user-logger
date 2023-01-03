<?php

namespace dnj\UserLogger;

use Illuminate\Database\Eloquent\Model;

class ModelUtils
{
    public static function changesForLog(Model $model): ?array
    {
        $dirties = $model->getDirty();
        if (!$dirties) {
            return null;
        }

        $changes = [
            'attributes' => $dirties,
            'olds' => [],
        ];

        foreach (array_keys($dirties) as $key) {
            $changes['olds'][$key] = $model->getRawOriginal($key);
        }

        return $changes;
    }
}
