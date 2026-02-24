<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopeTargetBulanan
{
    public function scopeTargetBulanan(Builder $query, string $bulan)
    {
        $column = "(target_bulanan->>'{$bulan}')";

        return $query->whereRaw("{$column}::FLOAT > 0 AND {$column} IS NOT NULL");
    }
}
