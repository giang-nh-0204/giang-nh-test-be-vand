<?php

namespace App\Repositories\Interfaces;

use App\Repositories\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getProducts($storeId, $status, $limit);

    public function search($keyword, $limit);

    public function getDetail($productId);
}
