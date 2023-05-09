<?php

namespace App\CustomHelper\HelperClasses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileLogger
{
    public static function info($object,Request $request ,$msg = null)
    {
        $routeName = $request->url();
        $method = $request->method();
        $requestBody = $object;
        $logMessage = "Route: $routeName | Method: $method | Request: $requestBody | Message: $msg";
        Log::channel('file')->info($logMessage);
    }

    public static function error($object,Request $request, $msg = null)
    {
        $routeName = $request->url();
        $method = $request->method();
        $requestBody = $object;
        $logMessage = "Route: $routeName | Method: $method | Request: $requestBody | Message: $msg ";
        Log::channel('file')->error($logMessage);
    }

}
