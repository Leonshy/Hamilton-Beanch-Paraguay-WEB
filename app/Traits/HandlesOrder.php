<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HandlesOrder
{
    private function nextOrder(string $modelClass, ?string $scopeColumn = null, ?string $scopeValue = null): int
    {
        $query = $modelClass::query();
        if ($scopeColumn && $scopeValue !== null) {
            $query->where($scopeColumn, $scopeValue);
        }
        return (int) $query->max('order') + 1;
    }

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

    protected function applyReorder(string $modelClass, array $ids, ?string $scopeColumn = null, ?string $scopeValue = null): void
    {
        foreach ($ids as $position => $id) {
            $modelClass::query()
                ->where('id', $id)
                ->when($scopeColumn && $scopeValue !== null, fn($q) => $q->where($scopeColumn, $scopeValue))
                ->update(['order' => $position + 1]);
        }
    }
}
