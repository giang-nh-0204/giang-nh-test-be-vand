<?php

namespace App\Services;

use App\Repositories\Interfaces\StoreRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreService extends BaseService
{
    protected $storeRepository;
    protected $userRepository;

    public function __construct(StoreRepositoryInterface $storeRepository, UserRepositoryInterface $userRepository)
    {
        parent::__construct();

        $this->storeRepository = $storeRepository;
        $this->userRepository  = $userRepository;
    }

    /**
     * @param $data
     * @return array|string[]
     */
    public function create($data): array
    {
        try {
            DB::beginTransaction();
            $this->storeRepository->create($data);
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
     * @param $storeId
     * @param $data
     * @return array|string[]
     */
    public function update($storeId, $data): array
    {
        try {
            $store = $this->storeRepository->find($storeId);

            // Khi không tim thấy cửa hàng
            if (!$store) {
                Log::error($this->message['STORE']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['STORE']['NOT_FOUND']
                ];
            }

            // Khi user đăng nhập không phải là user được assign cho cửa hàng
            if ($store['user_id'] != Auth::id()) {
                Log::error('Not Permission');
                return [
                    'status'  => $this->httpStatusCode['403'],
                    'message' => $this->httpStatusText['403']
                ];
            }

            // Khi cập nhật tên cửa hàng và tên mới đã tồn tại
            if ($store['name'] != $data['name'] && $this->storeRepository->getByName($data['name'])) {
                return [
                    'success' => false,
                    'message' => $this->message['STORE']['NAME_EXISTS']
                ];
            }

            DB::beginTransaction();

            $updated = $this->storeRepository->update($storeId, $data);

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
     * @param $storeId
     * @return array|string[]
     */
    public function delete($storeId): array
    {
        try {
            $store = $this->storeRepository->find($storeId);

            // Khi cửa hàng không tồn tại
            if (!$store) {
                Log::error($this->message['STORE']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['STORE']['NOT_FOUND']
                ];
            }

            // Khi user đăng nhập không phải là user được assign cho cửa hàng
            if ($store['user_id'] != Auth::id()) {
                Log::error('Not Permission');
                return [
                    'status'  => $this->httpStatusCode['403'],
                    'message' => $this->httpStatusText['403']
                ];
            }

            DB::beginTransaction();

            $deleted = $this->storeRepository->delete($storeId);

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
     * @param $userId
     * @param $limit
     * @return array|array[]
     */
    public function getStores($userId, $limit): array
    {
        try {
            $user = $this->userRepository->getDetail($userId);

            // Khi không tìm thấy user - active
            if (!$user) {
                Log::error($this->message['USER']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['USER']['NOT_FOUND']
                ];
            }

            // Khi user assign cửa hàng không phải là user đăng nhập thì chỉ lấy được danh sách cửa hàng đã active
            $status = $userId != Auth::id() ? ['active'] : ['active', 'inactive'];

            $paginationStores = $this->storeRepository->getStores($userId, $status, $limit);

            return [
                'data' => ['paginationStores' => $paginationStores]
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
            $paginationStores = $this->storeRepository->search($keyword, $limit);

            return [
                'data' => ['paginationStores' => $paginationStores]
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
     * @param $storeId
     * @return array|array[]
     */
    public function getDetail($storeId): array
    {
        try {
            $store = $this->storeRepository->find($storeId);

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
                $store = $this->storeRepository->getDetail($storeId);

                // Khi không tìm thấy cửa hàng được active
                if (!$store) {
                    Log::error($this->message['STORE']['NOT_FOUND']);
                    return [
                        'status'  => $this->httpStatusCode['404'],
                        'message' => $this->message['STORE']['NOT_FOUND']
                    ];
                }
            }

            return [
                'data' => ['store' => $store]
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
     * @param $storeId
     * @param $status
     * @return array|string[]
     */
    public function updateStatus($storeId, $status): array
    {
        try {
            $store = $this->storeRepository->find($storeId);

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
            if ($store['status'] == $status) {
                Log::error($this->message['STATUS_UPDATED_ERROR']);
                return [
                    'success' => false,
                    'message' => $this->message['STATUS_UPDATED_ERROR']
                ];
            }

            DB::beginTransaction();

            $updated = $this->storeRepository->updateStatus($storeId, $status);

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
