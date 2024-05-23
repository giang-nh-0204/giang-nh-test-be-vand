<?php

namespace App\Repositories\Interfaces;

use App\Repositories\BaseRepositoryInterface;

interface StoreRepositoryInterface extends BaseRepositoryInterface
{
    public function getByName($name);

    public function getStores($userId, $status, $limit);

    public function search($keyword, $limit);

    public function getDetail($storeId);
}
