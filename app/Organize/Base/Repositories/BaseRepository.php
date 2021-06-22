<?php

namespace App\Organize\Base\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Organize\Base\Repositories\Contracts\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $id
     *
     * @return Model
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     *
     * @return Model
     */
    public function findOrFail(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @return Model
     */
    public function firstOrFail(): ?Model
    {
        return $this->model->firstOrFail($id);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return $this->model->delete();
    }
}
