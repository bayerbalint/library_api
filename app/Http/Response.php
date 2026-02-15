<?php

namespace App\Http;

class Response
{
    public static function ok(array $data = []): void {
        self::send($data, 200);
    }

    public static function created(array $data = []): void {
        self::send($data, 201);
    }

    public static function error(string $message, int $code = 400): void {
        self::send(['error' => $message], $code);
    }

    public static function deleted(string $message = "deleted", int $code = 204): void {
        self::send(['message' => $message], $code);
    }

    public static function updated(string $message = "updated", int $code = 202): void {
        self::send(['message' => $message], $code);
    }



    private static function send(array $data, int $code): void {
        http_response_code($code);
        header('Content-Type: application/json');

        if ($data != null && !key_exists("error", $data)){
            for ($i = 0; $i < count($data); $i++){
                foreach ($data[$i] as $key => $value){
                    if ($key == "coverImage"){
                        $data[$i][$key] = base64_encode($data[$i][$key]);
                    }
                }
            }
        }
        
        echo json_encode(['code' => $code, 'count' => count($data)] + $data, JSON_THROW_ON_ERROR);
        exit;
    }
}