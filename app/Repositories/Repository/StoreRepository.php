<?php

namespace App\Repositories\Repository;

use App\Models\Store;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\StoreRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class StoreRepository extends BaseRepository implements StoreRepositoryInterface
{
    protected $instance;

    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->instance = $store;
    }

    /**
     * @param $name
     * @return Store|Model|object|null
     */
    public function getByName($name)
    {
        return $this->instance
            ->where('stores.name', $name)
            ->where('stores.delete_flg', 0)
            ->select(
                'stores.id',
                'stores.user_id',
                'stores.name',
                'stores.description',
                'stores.address'
            )
            ->first();
    }

    /**
     * @param $userId
     * @param $status
     * @param $limit
     * @return LengthAwarePaginator
     */
    public function getStores($userId, $status, $limit): LengthAwarePaginator
    {
        return $this->instance
            ->where('stores.user_id', $userId)
            ->whereIn('stores.status', $status)
            ->where('stores.delete_flg', 0)
            ->select(
                'stores.id',
                'stores.user_id',
                'stores.name',
                'stores.description',
                'stores.address'
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
                $query->where('stores.name', 'like', "%{$keyword}%")
                      ->orWhere('stores.description', 'like', "%{$keyword}%");
            })
            ->where('stores.status', 'active')
            ->where('stores.delete_flg', 0)
            ->select(
                'stores.id',
                'stores.user_id',
                'stores.name',
                'stores.description',
                'stores.address'
            )
            ->paginate($limit);
    }

    /**
     * @param $storeId
     * @return Store|Model|object|null
     */
    public function getDetail($storeId)
    {
        return $this->instance
            ->where('stores.id', $storeId)
            ->where('stores.status', 'active')
            ->where('stores.delete_flg', 0)
            ->select(
                'stores.id',
                'stores.user_id',
                'stores.name',
                'stores.description',
                'stores.address'
            )
            ->first();
    }
}
