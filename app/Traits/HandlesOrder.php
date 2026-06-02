<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HandlesOrder
{
    /**
     * Siguiente orden disponible (max + 1).
     * Si $scopeColumn está definido, lo calcula dentro de ese scope (ej: banners por position).
     */
    private function nextOrder(string $modelClass, ?string $scopeColumn = null, ?string $scopeValue = null): int
    {
        $query = $modelClass::query();
        if ($scopeColumn && $scopeValue !== null) {
            $query->where($scopeColumn, $scopeValue);
        }
        return (int) $query->max('order') + 1;
    }

    /**
     * Desplaza hacia arriba todos los registros con order >= $order
     * (excepto el registro con $ignoreId) para hacer lugar al nuevo valor.
     */
    private function shiftOrderUp(string $modelClass, int $order, ?int $ignoreId = null, ?string $scopeColumn = null, ?string $scopeValue = null): void
    {
        $exists = $modelClass::query()
            ->where('order', $order)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->when($scopeColumn && $scopeValue !== null, fn($q) => $q->where($scopeColumn, $scopeValue))
            ->exists();

        if ($exists) {
            $modelClass::query()
                ->where('order', '>=', $order)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->when($scopeColumn && $scopeValue !== null, fn($q) => $q->where($scopeColumn, $scopeValue))
                ->increment('order');
        }
    }
}
