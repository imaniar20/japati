<?php

namespace App\Supports;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Pagination\LengthAwarePaginator as PaginationLengthAwarePaginator;

class LengthAwarePaginator extends PaginationLengthAwarePaginator
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->toArrayValues($this->items),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArrayValues($items)
    {
        return $items->map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        })->values()->all();
    }
}
