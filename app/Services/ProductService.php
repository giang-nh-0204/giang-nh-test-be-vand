<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StoreRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService extends BaseService
{
    protected $storeRepository;
    protected $productRepository;
    protected $userRepository;

    public function __construct(ProductRepositoryInterface $productRepository, StoreRepositoryInterface $storeRepository, UserRepositoryInterface $userRepository)
    {
        parent::__construct();

        $this->productRepository = $productRepository;
        $this->storeRepository   = $storeRepository;
        $this->userRepository    = $userRepository;
    }

    /**
     * @param $data
     * @return array|string[]
     */
    public function create($data): array
    {
        try {
            $store = $this->storeRepository->find($data['store_id']);

            // Khi không tìm thấy cửa hàng
            if (!$store) {
                Log::error($this->message['STORE']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['STORE']['NOT_FOUND']
                ];
            }

            // Khi user assign cửa hàng không phải là user đăng nhập
            if ($store['user_id'] != Auth::id()) {
                Log::error('Not Permission');
                return [
                    'status'  => $this->httpStatusCode['403'],
                    'message' => $this->httpStatusText['403']
                ];
            }

            DB::beginTransaction();
            $this->productRepository->create($data);
            DB::commit();

            return [
                'message' => $this->message['CREATED']
            ];

        }
        catch (\Exception|\Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return [
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            ];
        }
    }

    /**
     * @param $productId
     * @param $data
     * @return array|string[]
     */
    public function update($productId, $data): array
    {
        try {
            $product = $this->productRepository->find($productId);

            // Khi không tìm thấy sản phẩm
            if (!$product) {
                Log::error($this->message['PRODUCT']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['PRODUCT']['NOT_FOUND']
                ];
            }

            $store = $this->storeRepository->find($product['store_id']);

            // Khi không tìm thấy cửa hàng
            if (!$store) {
                Log::error($this->message['STORE']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['STORE']['NOT_FOUND']
                ];
            }

            // Khi user assign cửa hàng không phải là user đăng nhập
            if ($store['user_id'] != Auth::id()) {
                Log::error('Not Permission');
                return [
                    'status'  => $this->httpStatusCode['403'],
                    'message' => $this->httpStatusText['403']
                ];
            }

            DB::beginTransaction();

            $updated = $this->productRepository->update($productId, $data);

            // Khi cập nhật không thành công
            if (!$updated) {
                Log::error($this->message['UPDATE_FAILED']);
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => $this->message['UPDATE_FAILED']
                ];
            }

            DB::commit();

            return [
                'message' => $this->message['UPDATED']
            ];

        }
        catch (\Exception|\Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return [
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            ];
        }
    }

    /**
     * @param $productId
     * @return array|string[]
     */
    public function delete($productId): array
    {
        try {
            $product = $this->productRepository->find($productId);

            // Khi không tìm thấy sản phẩm
            if (!$product) {
                Log::error($this->message['PRODUCT']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['PRODUCT']['NOT_FOUND']
                ];
            }

            $store = $this->storeRepository->find($product['store_id']);

            // Khi không tìm thấy cửa hàng
            if (!$store) {
                Log::error($this->message['STORE']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['STORE']['NOT_FOUND']
                ];
            }

            // Khi user assign cửa hàng không phải là user đăng nhập
            if ($store['user_id'] != Auth::id()) {
                Log::error('Not Permission');
                return [
                    'status'  => $this->httpStatusCode['403'],
                    'message' => $this->httpStatusText['403']
                ];
            }

            DB::beginTransaction();

            $deleted = $this->productRepository->delete($productId);

            // Khi xóa không thành công
            if (!$deleted) {
                Log::error($this->message['DELETE_FAILED']);
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => $this->message['DELETE_FAILED']
                ];
            }

            DB::commit();

            return [
                'message' => $this->message['DELETED']
            ];

        }
        catch (\Exception|\Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return [
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            ];
        }
    }

    /**
     * @param $storeId
     * @param $limit
     * @return array|array[]
     */
    public function getProducts($storeId, $limit): array
    {
        try {
            $store = $this->storeRepository->getDetail($storeId);

            // Khi không tìm thấy cửa hàng - active
            if (!$store) {
                Log::error($this->message['STORE']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['STORE']['NOT_FOUND']
                ];
            }

            $user = $this->userRepository->getDetail($store['user_id']);

            // Khi không tìm thấy user - active
            if (!$user) {
                Log::error($this->message['USER']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['USER']['NOT_FOUND']
                ];
            }

            // Khi user assign cửa hàng không phải là user đăng nhập thì chỉ lấy được danh sách sản phẩm đã active
            $status = $store['user_id'] != Auth::id() ? ['active'] : ['active', 'inactive'];

            $paginationProducts = $this->productRepository->getProducts($storeId, $status, $limit);

            return [
                'data' => ['paginationProducts' => $paginationProducts]
            ];

        }
        catch (\Exception|\Throwable $e) {
            Log::error($e);
            return [
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            ];
        }
    }

    /**
     * @param $keyword
     * @param $limit
     * @return array|array[]
     */
    public function search($keyword, $limit): array
    {
        try {
            $paginationProducts = $this->productRepository->search($keyword, $limit);

            return [
                'data' => ['paginationProducts' => $paginationProducts]
            ];

        }
        catch (\Exception|\Throwable $e) {
            Log::error($e);
            return [
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            ];
        }
    }

    /**
     * @param $productId
     * @return array|array[]
     */
    public function getDetail($productId): array
    {
        try {
            $product = $this->productRepository->find($productId);

            // Khi không tìm thấy sản phẩm
            if (!$product) {
                Log::error($this->message['PRODUCT']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['PRODUCT']['NOT_FOUND']
                ];
            }

            $store = $this->storeRepository->find($product['store_id']);

            // Khi không tìm thấy cửa hàng
            if (!$store) {
                Log::error($this->message['STORE']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['STORE']['NOT_FOUND']
                ];
            }

            $user = $this->userRepository->getDetail($store['user_id']);

            // Khi không tìm thấy user được active
            if (!$user) {
                Log::error($this->message['USER']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['USER']['NOT_FOUND']
                ];
            }

            // Khi user lấy thông tin không phải là user assign của cửa hàng
            if ($store['user_id'] != Auth::id()) {
                $product = $this->productRepository->getDetail($productId);

                // Khi không tìm thấy sản phẩm được active
                if (!$product) {
                    Log::error($this->message['PRODUCT']['NOT_FOUND']);
                    return [
                        'status'  => $this->httpStatusCode['404'],
                        'message' => $this->message['PRODUCT']['NOT_FOUND']
                    ];
                }
            }

            return [
                'data' => ['product' => $product]
            ];

        }
        catch (\Exception|\Throwable $e) {
            Log::error($e);
            return [
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            ];
        }
    }

    /**
     * @param $productId
     * @param $status
     * @return array|string[]
     */
    public function updateStatus($productId, $status): array
    {
        try {
            $product = $this->productRepository->find($productId);

            // Khi không tìm thấy sản phẩm
            if (!$product) {
                Log::error($this->message['PRODUCT']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['PRODUCT']['NOT_FOUND']
                ];
            }

            $store = $this->storeRepository->find($product['store_id']);

            // Khi không tìm thấy cửa hàng
            if (!$store) {
                Log::error($this->message['STORE']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['STORE']['NOT_FOUND']
                ];
            }

            // Khi user cập nhật trạng thái không phải là user assign của cửa hàng
            if ($store['user_id'] != Auth::id()) {
                Log::error('Not Permission');
                return [
                    'status'  => $this->httpStatusCode['403'],
                    'message' => $this->httpStatusText['403']
                ];
            }

            // Khi trạng thái không thay đổi
            if ($product['status'] == $status) {
                Log::error($this->message['STATUS_UPDATED_ERROR']);
                return [
                    'success' => false,
                    'message' => $this->message['STATUS_UPDATED_ERROR']
                ];
            }

            DB::beginTransaction();

            $updated = $this->productRepository->updateStatus($productId, $status);

            // Khi cập nhật không thành công
            if (!$updated) {
                Log::error($this->message['UPDATE_FAILED']);
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => $this->message['UPDATE_FAILED']
                ];
            }

            DB::commit();

            return [
                'message' => $this->message['UPDATED']
            ];

        }
        catch (\Exception|\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return array(
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            );
        }
    }
}
