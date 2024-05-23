<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $responseHelper;
    protected $productService;

    public function __construct(ProductService $productService, ResponseHelper $responseHelper)
    {
        parent::__construct();

        $this->productService = $productService;
        $this->responseHelper = $responseHelper;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->only([
                                   'store_id',
                                   'name',
                                   'description',
                                   'price',
                                   'quantity'
                               ]);

        $validator = Validator::make($data, [
            'store_id'    => 'required|int',
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'quantity'    => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return $this->responseHelper->responseBadRequest();
        }

        return $this->responseHelper->responseJson(
            $this->productService->create($data)
        );
    }

    /**
     * @param Request $request
     * @param $productId
     * @return JsonResponse
     */
    public function update(Request $request, $productId): JsonResponse
    {
        $data = $request->only([
                                   'name',
                                   'price',
                                   'description',
                                   'quantity'
                               ]);

        $validator = Validator::make($data, [
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'quantity'    => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return $this->responseHelper->responseBadRequest();
        }

        return $this->responseHelper->responseJson(
            $this->productService->update($productId, $data)
        );
    }

    /**
     * @param $productId
     * @return JsonResponse
     */
    public function delete($productId): JsonResponse
    {
        return $this->responseHelper->responseJson(
            $this->productService->delete($productId)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getProducts(Request $request): JsonResponse
    {
        $data = $request->only('store_id', 'limit', 'page');

        $validator = Validator::make($data, [
            'store_id' => 'required|int',
            'limit'    => 'required|int',
            'page'     => 'required|int'
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return $this->responseHelper->responseBadRequest();
        }

        return $this->responseHelper->responseJson(
            $this->productService->getProducts($data['store_id'], $data['limit'])
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
            $this->productService->search($data['keyword'], $data['limit'])
        );
    }

    /**
     * @param $productId
     * @return JsonResponse
     */
    public function getDetail($productId): JsonResponse
    {
        return $this->responseHelper->responseJson(
            $this->productService->getDetail($productId)
        );
    }

    /**
     * @param Request $request
     * @param $productId
     * @return JsonResponse
     */
    public function updateStatus(Request $request, $productId): JsonResponse
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
            $this->productService->updateStatus($productId, $status)
        );
    }
}
