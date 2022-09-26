<?php

class ResponseService
{
    public function __construct()
    {
    }

    public function Send($status, $httpStatusCode, $message, $detail)
    {
        return json_encode([
            "status" => $status,
            "statusCode" => $httpStatusCode,
            "message" => $message,
            "detail" => $detail
        ]);
    }
}