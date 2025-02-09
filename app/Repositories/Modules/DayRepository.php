<?php

namespace App\Repositories\Modules;

use App\Models\Day;
use App\Repositories\BaseRepository;
use Faker\Provider\Base;

class DayRepository extends BaseRepository{
    public function __construct(Day $day)
    {
        parent::__construct($day);
    }

    public function getPaginateDayRepository($perPage= 10, string $latest = 'id'){
        return $this->model->latest($latest)->paginate($perPage);
    }
    public function findByIdDayRepository($id)
    {
        return $this->find($id);
    }

    public function createDRepository(array $data)
    {
        return $this->create($data);
    }

    public function updateDay($id, array $data)
    {
        $day = $this->findByIdDayRepository($id);
        return $day->update($data);
    }

    public function deleteDay($id)
    {
        $day = $this->findByIdDayRepository($id);
        return $day->delete();
    }
}