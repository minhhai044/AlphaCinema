<?php

namespace App\Repositories\Modules;

use App\Models\Movie;
use App\Repositories\BaseRepository;
use Faker\Provider\Base;

class MovieRepository extends BaseRepository{
    public function __construct(Movie $movie)
    {
        parent::__construct($movie);
    }

    public function getPaginateMovieRepository($perPage= 10, string $latest = 'id'){
        return $this->model->latest($latest)->paginate($perPage);
    }
    public function findByIdMovieRepository($id)
    {
        return $this->find($id);
    }

    public function createMovieMovieRepository(array $data)
    {
        return $this->create($data);
    }

    public function updateMovie($id, array $data)
    {
        $movie = $this->findByIdMovieRepository($id);
        return $movie->update($data);
    }

    public function deleteMovie($id)
    {
        $movie = $this->findByIdMovieRepository($id);
        return $movie->delete();
    }
}