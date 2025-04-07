<?php

namespace App\Http\Controllers\Filters;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    protected $query;
    protected $filters;

    public function __construct(Builder $query, array $filters)
    {
        $this->query = $query;
        $this->filters = $filters;
    }

    /**
     * Áp dụng các bộ lọc mặc định cho những key chưa được xử lý
     */
    public function apply(): Builder
    {
        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                $this->query->where($key, 'like', "%{$value}%");
            }
        }
        return $this->query;
    }
}