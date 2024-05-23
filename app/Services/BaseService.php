<?php

namespace App\Services;

class BaseService
{
    protected $httpStatusCode;
    protected $httpStatusText;
    protected $message;

    public function __construct()
    {
        $this->httpStatusCode = config('const.httpStatusCode');
        $this->httpStatusText = config('const.httpStatusText');
        $this->message        = config('const.message');

    }
}
