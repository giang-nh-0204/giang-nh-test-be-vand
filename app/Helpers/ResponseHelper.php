<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * @param array $data
     * @return JsonResponse
     */
    public function responseJson(array $data): JsonResponse
    {
        $status  = 200;
        $success = true;

        // gán status và success theo $data
        if (array_key_exists("status", $data)) $status = $data["status"];
        if (array_key_exists("success", $data)) $success = $data["success"];

        // gán data khi chưa tồn tại $data["data"]
        if (!array_key_exists("data", $data)) $data["data"] = [];
        if ($status != 200) $success = false;

        $data["success"] = $success;

        return response()->json($data, $status);
    }

    /**
     * @return JsonResponse
     */
    public function responseBadRequest(): JsonResponse
    {
        $validatorResponse = [
            "status"  => '400',
            "message" => '400',
            "success" => false
        ];

        return $this->responseJson($validatorResponse);
    }

    /**
     * @return JsonResponse
     */
    public function responseNotPermission(): JsonResponse
    {
        $response = [
            "status"  => '403',
            "message" => '403',
            "success" => false
        ];

        return $this->responseJson($response);

    }

}
