<?php

namespace App\Libraries;

class ResponseLibrary
{

    public static function successResponse($message = 'Success', $data = null, $code = 200)
    {
        $res = [
            'success'   => true,
            'code'      => $code,
            'message'   => $message,
            'data'      => $data,
            'error'     => null,
        ];
        return self::sendResponse($res, $code);
    }

    public static function unauthorizeResponse($message = 'Unauthorized', $error = null)
    {
        return self::errorResponse($message, $error, 401);
    }

    public static function internalErrorResponse($message = 'Internal server error', $error = null)
    {
        return self::errorResponse($message, $error, 500);
    }

    public static function errorResponse($message = 'Error', $error = null, $code = 500)
    {
        $res = [
            'success'   => false,
            'code'      => $code,
            'message'   => $message,
            'data'      => null,
            'error'     => $error,
        ];
        if (empty($error)) {
            $res['error'] = $message;
        }
        return self::sendResponse($res, $code);
    }

    public static function sendResponse($response, $code)
    {
        return response()->json($response, $code);
    }
}
