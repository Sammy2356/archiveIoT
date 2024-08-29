<?php

namespace app\dto;

class ResponseDto
{
    // public static function json($data, int $http_status_code = 200){
    //     header("http/1.1 $http_status_code");
    //     return json_encode([ "message" => $data]);
    // }



    static function json($message, int $status = 404, array $data = [])
    {
        http_response_code($status);
        return json_encode(["message" => $message, "status_code" => $status, "data" => $data]);
    }

    static function reform(string $response)
    {
        $res = json_decode($response, true);
        http_response_code($res["status_code"]);
        return json_encode($res["message"]);
    }
}
