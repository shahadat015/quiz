<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successResponse($message, $data = '')
    {
        return response()->json(['success' => true, 'message' => __($message), 'data' => $data]);
    }

    public function errorResponse($message)
    {
        return response()->json(['error' => true, 'message' => __($message)]);
    }
}
