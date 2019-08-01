<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;

abstract class Repository
{

    /**
     * @var object
     */
    protected $model;

    /**
     * @param array
     * @return object
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Method to find a record by its primary key.
     * @param int
     * @return object
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @param int
     * @param array
     * @return  int
     */
    public function update(int $id, array $attributes): int
    {
        $currentModel = $this->find($id);

        return $currentModel->update($attributes);
    }

    /**
     * @param int
     * @return int
     */
    public function destroy(int $id): int
    {
        return $this->model->destroy($id);
    }

    /**
     * @param array
     * @return Collection
     */
    public function where(array $attributes): Collection
    {
        return $this->model->where($attributes)->get();
    }

    public function getAll()
    {
        return $this->model->get();
    }

}
