<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    protected $responseHelper;
    protected $userService;

    public function __construct(UserService $userService, ResponseHelper $responseHelper)
    {
        parent::__construct();

        $this->responseHelper = $responseHelper;
        $this->userService    = $userService;

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->only(['email', 'password']);

        $validator = Validator::make($data, [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return $this->responseHelper->responseBadRequest();
        }

        return $this->responseHelper->responseJson(
            $this->userService->login($data)
        );
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->responseHelper->responseJson(
            $this->userService->logout()
        );
    }

    /**
     * @param Request $request
     * @param $userId
     * @return JsonResponse
     */
    public function updateStatus(Request $request, $userId): JsonResponse
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
            $this->userService->updateStatus($userId, $status)
        );
    }
}
