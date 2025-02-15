<?php

namespace App\Repositories\Modules;

use App\Models\Rank;
use App\Repositories\BaseRepository;

class RankRepository extends BaseRepository
{
    public function __construct(Rank $rank)
    {
        parent::__construct($rank);
    }

    public function getPaginateRepository($perPage = 10, string $latest = 'id')
    {
        return $this->model->latest($latest)->paginate($perPage);
    }

    
}
