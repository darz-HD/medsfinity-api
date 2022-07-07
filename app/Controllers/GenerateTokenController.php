<?php
namespace App\Controllers;
use Firebase\JWT\JWT;

class GenerateTokenController
{
    public static function generateToken($email)
    {
        $now = time();
        $future = strtotime('+1 hour',$now);
        $secretKey = $_ENV["JWT_SECRET"];
        $payload = [
         "jti"=>$email,
         "iat"=>$now,
         "exp"=>$future
        ];

        return JWT::encode($payload,$secretKey,"HS256");
    }
}