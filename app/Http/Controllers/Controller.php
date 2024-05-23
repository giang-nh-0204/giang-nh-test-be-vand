<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $httpStatusCode;
    protected $httpStatusText;

    public function __construct()
    {
        $this->httpStatusCode = config('const.httpStatusCode');
        $this->httpStatusText = config('const.httpStatusText');
    }
}
