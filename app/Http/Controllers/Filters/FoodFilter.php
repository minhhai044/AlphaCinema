<?php

namespace App\Http\Controllers\Filters;

use App\Models\Food;
use Illuminate\Database\Eloquent\Builder;

class FoodFilter extends Filter
{
    public function __construct(array $filters)
    {
        // Khởi tạo truy vấn từ model FoodFilter
        parent::__construct(Food::query(), $filters);
    }

    public function apply(): Builder
    {
        //lọc theo id
        $this->query->when(!empty($this->filters['id']), function (Builder $query) {
            $query->where('id', $this->filters['id']);
        });

        // Lọc theo tên phim
        $this->query->when(!empty($this->filters['name']), function (Builder $query) {
            $query->where('name', 'like', "%{$this->filters['name']}%");
        });

        // Lọc theo loại đồ ăn
        $this->query->when(!empty($this->filters['type']), function (Builder $query) {
            $query->where('type', $this->filters['type']);
        });

        // Xóa các key đã xử lý để tránh lặp lại trong bộ lọc mặc định của base
        unset($this->filters['name'], $this->filters['type']);

        // Áp dụng thêm các bộ lọc mặc định (nếu có key khác)
        return parent::apply();
    }
}