<?php

namespace App\Http\Controllers\Filters;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Builder;

class MovieFilter extends Filter
{
    public function __construct(array $filters)
    {
        // Khởi tạo truy vấn từ model Movie
        parent::__construct(Movie::query(), $filters);
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

        // Lọc theo phiên bản
        $this->query->when(!empty($this->filters['version']), function (Builder $query) {
            $query->where('version', $this->filters['version']);
        });

        // Lọc theo thể loại
        $this->query->when(!empty($this->filters['genre']), function (Builder $query) {
            $query->where('genre', $this->filters['genre']);
        });

        // Xóa các key đã xử lý để tránh lặp lại trong bộ lọc mặc định của base
        unset($this->filters['title'], $this->filters['version'], $this->filters['genre']);

        // Áp dụng thêm các bộ lọc mặc định (nếu có key khác)
        return parent::apply();
    }
}