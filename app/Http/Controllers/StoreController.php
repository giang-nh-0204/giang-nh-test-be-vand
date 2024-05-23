<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    protected $responseHelper;
    protected $storeService;

    public function __construct(StoreService $storeService, ResponseHelper $responseHelper)
    {
        parent::__construct();

        $this->storeService   = $storeService;
        $this->responseHelper = $responseHelper;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->only([
                                   'user_id',
                                   'name',
                                   'description',
                                   'address'
                               ]);

        $validator = Validator::make($data, [
            'user_id'     => 'required|int',
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'address'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return $this->responseHelper->responseBadRequest();
        }

        // Khi user assign cho cửa hàng không phải user đăng nhập
        if ($data['user_id'] != Auth::id()) {
            Log::error('Not Permission');
            return $this->responseHelper->responseNotPermission();
        }

        return $this->responseHelper->responseJson(
            $this->storeService->create($data)
        );
    }

    /**
     * @param Request $request
     * @param $storeId
     * @return JsonResponse
     */
    public function update(Request $request, $storeId): JsonResponse
    {
        $data = $request->only([
                                   'name',
                                   'description',
                                   'address'
                               ]);

        $validator = Validator::make($data, [
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'address'     => 'nullable|string'
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return $this->responseHelper->responseBadRequest();
        }

        return $this->responseHelper->responseJson(
            $this->storeService->update($storeId, $data)
        );
    }

    /**
     * @param $storeId
     * @return JsonResponse
     */
    public function delete($storeId): JsonResponse
    {
        return $this->responseHelper->responseJson(
            $this->storeService->delete($storeId)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getStores(Request $request): JsonResponse
    {
        $data = $request->only('user_id', 'limit', 'page');

        $validator = Validator::make($data, [
            'user_id' => 'required|int',
            'limit'   => 'required|int',
            'page'    => 'required|int'
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return $this->responseHelper->responseBadRequest();
        }

        return $this->responseHelper->responseJson(
            $this->storeService->getStores($data['user_id'], $data['limit'])
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $data = $request->only('keyword', 'limit', 'page');

        $validator = Validator::make($data, [
            'keyword' => 'required|string',
            'limit'   => 'required|int',
            'page'    => 'required|int'
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return $this->responseHelper->responseBadRequest();
        }

        return $this->responseHelper->responseJson(
            $this->storeService->search($data['keyword'], $data['limit'])
        );
    }

    /**
     * @param $storeId
     * @return JsonResponse
     */
    public function getDetail($storeId): JsonResponse
    {
        return $this->responseHelper->responseJson(
            $this->storeService->getDetail($storeId)
        );
    }

    /**
     * @param Request $request
     * @param $storeId
     * @return JsonResponse
     */
    public function updateStatus(Request $request, $storeId): JsonResponse
    {
        $status = $request->input('status');

        $validator = Validator::make(
            ['status' => $status],
            ['status' => 'required|in:active,inactive']
        );

        if ($validator->fails()) {
            Log::error($validator->errors());
            return $this->responseHelper->responseBadRequest();
        }

        return $this->responseHelper->responseJson(
            $this->storeService->updateStatus($storeId, $status)
        );
    }

}
