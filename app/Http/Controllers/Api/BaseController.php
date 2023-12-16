<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $isAdmin = false;

    public function __construct(Request $request)
    {
        if (auth()->guard('api')?->user()?->role->name == 'Super Admin') {
            $this->isAdmin = true;
            $request['role'] = 'Super Admin';
        }
    }

    /**
     * success response method.
     * @return \Illuminate\Http\Response
     */

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'status' => 'success',
            'message' => $message,
            'data'    => $result,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 302)
    {
        $response = [
            'status' => 'failed',
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
