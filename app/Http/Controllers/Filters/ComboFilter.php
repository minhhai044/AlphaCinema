<?php

namespace App\Http\Controllers\Filters;

use App\Models\Combo;
use Illuminate\Database\Eloquent\Builder;

class ComboFilter extends Filter
{
    public function __construct(array $filters)
    {
        // Khởi tạo truy vấn từ model Combo
        parent::__construct(Combo::query(), $filters);
    }

    public function apply(): Builder
    {
        //lọc theo id
        $this->query->when(!empty($this->filters['id']), function (Builder $query) {
            $query->where('id', $this->filters['id']);
        });

        // Lọc theo tên 
        $this->query->when(!empty($this->filters['name']), function (Builder $query) {
            $query->where('name', 'like', "%{$this->filters['name']}%");
        });

        // Xóa các key đã xử lý để tránh lặp lại trong bộ lọc mặc định của base
        unset($this->filters['title'], $this->filters['name']);

        // Áp dụng thêm các bộ lọc mặc định (nếu có key khác)
        return parent::apply();
    }
}