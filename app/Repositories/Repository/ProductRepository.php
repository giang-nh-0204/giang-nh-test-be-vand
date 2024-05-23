<?php

namespace App\Repositories\Repository;

use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $instance;

    public function __construct(Product $product)
    {
        parent::__construct($product);

        $this->instance = $product;
    }

    /**
     * @param $storeId
     * @param $status
     * @param $limit
     * @return LengthAwarePaginator
     */
    public function getProducts($storeId, $status, $limit): LengthAwarePaginator
    {
        return $this->instance
            ->where('products.store_id', $storeId)
            ->whereIn('products.status', $status)
            ->where('products.delete_flg', 0)
            ->select(
                'products.id',
                'products.store_id',
                'products.name',
                'products.description',
                'products.price',
                'products.quantity'
            )
            ->paginate($limit);
    }

    /**
     * @param $keyword
     * @param $limit
     * @return LengthAwarePaginator
     */
    public function search($keyword, $limit): LengthAwarePaginator
    {
        return $this->instance
            ->where(function ($query) use ($keyword) {
                $query->where('products.name', 'like', "%{$keyword}%")
                      ->orWhere('products.description', 'like', "%{$keyword}%");
            })
            ->where('products.status', 'active')
            ->where('products.delete_flg', 0)
            ->select(
                'products.id',
                'products.store_id',
                'products.name',
                'products.description',
                'products.price',
                'products.quantity'
            )
            ->paginate($limit);
    }

    /**
     * @param $productId
     * @return Product|Model|object|null
     */
    public function getDetail($productId)
    {
        return $this->instance
            ->where('products.id', $productId)
            ->where('products.status', 'active')
            ->where('products.delete_flg', 0)
            ->select(
                'products.id',
                'products.store_id',
                'products.name',
                'products.description',
                'products.price',
                'products.quantity'
            )
            ->first();
    }
}
