<?php
namespace App\Controllers;
use Firebase\JWT\JWT;

class GenerateTokenController
{
    public static function generateMerchantToken($id, $merchant)
    {
        $now = time();
        $secretKey = $_ENV["JWT_SECRET"];
        $payload = [
         "jti"=>$id,
         "iat"=>$now,
         "merchant_id"=>$id,
         "merchant"=>$merchant,
        ];

        return JWT::encode($payload,$secretKey,"HS256");
    }
    public static function generateLoginToken($id, $email)
    {
        $now = time();
        $future = strtotime('+1 hour',$now);
        $secretKey = $_ENV["JWT_SECRET"];
        $payload = [
         "jti"=>$id,
         "iat"=>$now,
         "user_id"=>$id,
         "email"=>$email,
         "exp"=>$future
        ];

        return JWT::encode($payload,$secretKey,"HS256");
    }
}