<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection|Model[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model
            ->where('delete_flg', 0)
            ->find($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data): bool
    {
        $model = $this->find($id);
        if ($model) return $model->update($data);
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->update($id, ['delete_flg' => 1]);
    }

    /**
     * @param $id
     * @param $status
     * @return bool
     */
    public function updateStatus($id, $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }
}
