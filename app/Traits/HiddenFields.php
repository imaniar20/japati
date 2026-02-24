<?php

namespace App\Traits;

trait HiddenFields
{
    /**
     * Get all hidden field from model,
     * useful when replicate model
     */
    public static function getHiddenFields(): array
    {
        return (new static)->hidden;
    }
}
