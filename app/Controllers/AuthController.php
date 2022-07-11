<?php

namespace  App\Controllers;

use App\Models\User;
use App\Models\Merchant;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use App\Validation\Validator;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface AS Response;
use Respect\Validation\Validator as v;

class AuthController
{

    protected $user;
    protected $customResponse;
    protected $validator;

    public function __construct()
    {
        $this->user = new User();
        $this->merchant = new Merchant();
        $this->customResponse = new CustomResponse();
        $this->validator = new Validator();
    }


    public function Register(Request $request, Response $response)
    {
       $this->validator->validate($request,[
        "id"=>v::notEmpty(),
        "first_name"=>v::notEmpty(),
        "last_name"=>v::notEmpty(),
        "email"=>v::notEmpty()->email(),
        "password"=>v::notEmpty(),
        "status"=>v::notEmpty(),
       ]);

       if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }

       if($this->EmailExist(CustomRequestHandler::getParam($request,"email")))
       {
           $responseMessage = "Email already exist";
           return $this->customResponse->is400Response($response,$responseMessage);
       }

       $passwordHash = $this->hashPassword(CustomRequestHandler::getParam($request,'password'));

       $this->user->create([
        'id' => CustomRequestHandler::getParam($request, "id"),
        'first_name' => CustomRequestHandler::getParam($request, "first_name"),
        'last_name' => CustomRequestHandler::getParam($request, "last_name"),
        'email' => CustomRequestHandler::getParam($request, "email"),
        "password"=>$passwordHash,
        'status' => CustomRequestHandler::getParam($request, "status"),
       ]);

       $responseMessage = "new user created successfully";

       return $this->customResponse->is200Response($response,$responseMessage);
    }

    public function Login(Request $request,Response $response)
    {
        $this->validator->validate($request,[
            "email"=>v::notEmpty()->email(),
            "password"=>v::notEmpty()
        ]);

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }
        $verifyAccount = $this->verifyAccount(
            CustomRequestHandler::getParam($request,"password"),
            CustomRequestHandler::getParam($request,"email"));

        if($verifyAccount==false)
        {
            $responseMessage = "invalid username or password";
            return $this->customResponse->is400Response($response,$responseMessage);
        } else {
            $user = $this->user->where(["email"=>CustomRequestHandler::getParam($request,"email")])->first();
            $responseMessage = GenerateTokenController::generateLoginToken(
                $user->id,
                CustomRequestHandler::getParam($request,"email")
            );
            return $this->customResponse->is200Response($response,$responseMessage);
        }
    }
    public function verifyAccount($password,$email)
    {
        $count = $this->user->where(["email"=>$email])->count();
        if($count==0)
        {
            return false;
        }
        $user = $this->user->where(["email"=>$email])->first();
        $hashedPassword = $user->password;
        $verify = password_verify($password,$hashedPassword);
        if($verify==false)
        {
            return false;
        }
        return true;
    }
    public function AdminToken(Request $request,Response $response)
    {
        $admin = $this->merchant->where(["merchant"=>$_ENV["ADMIN_MERCHANT_NAME"]])->first();

        if(empty($admin)) {
            $data = $this->merchant->create([
                'merchant' => $_ENV["ADMIN_MERCHANT_NAME"],
                'key' => $_ENV["ADMIN_MERCHANT_NAME"],
            ]);

            $adminToken = GenerateTokenController::generateMerchantToken(
                $data->id,
                $data->merchant
            );
            $this->merchant->where(["id"=>$data->id])->update([
                'token' => $adminToken,
            ]);

            $responseMessage = ['id'=>$data->id, 'merchant'=>$data->merchant, 'key'=>$data->key, 'token'=>$adminToken];
            return $this->customResponse->is200Response($response, $responseMessage);
        } else {
            $responseMessage = ['id'=>$admin->id, 'merchant'=>$admin->merchant, 'key'=>$admin->key, 'token'=>$admin->token];
            return $this->customResponse->is200Response($response, $responseMessage);
        }
    }
    public function Token(Request $request,Response $response)
    {
        $this->validator->validate($request,[
            "merchant"=>v::notEmpty(),
            "key"=>v::notEmpty()
        ]);
        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }
        $verifyMerchant = $this->verifyMerchant(
            CustomRequestHandler::getParam($request,"merchant"),
            CustomRequestHandler::getParam($request,"key"));
            
        if($verifyMerchant==false)
        {
            $responseMessage = "invalid merchant";
            return $this->customResponse->is400Response($response,$responseMessage);
        } else {
            $merchant = $this->merchant->where(["merchant"=>CustomRequestHandler::getParam($request,"merchant")])->first();
            // check if merchant already has token
            $token = $merchant->token;
            if (empty($token))
            {
                $responseMessage = GenerateTokenController::generateMerchantToken(
                    $merchant->id,
                    CustomRequestHandler::getParam($request,"merchant")
                );
                $this->merchant->where(["merchant"=>CustomRequestHandler::getParam($request,"merchant")])->update([
                    'token' => $responseMessage,
                ]);
            } else {
                $responseMessage = $token;
            }
            
            return $this->customResponse->is200Response($response,$responseMessage);
        }
    }
    public function ResetToken(Request $request,Response $response)
    {
        $this->validator->validate($request,[
            "merchant"=>v::notEmpty(),
            "key"=>v::notEmpty()
        ]);
        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }
        $verifyMerchant = $this->verifyMerchant(
            CustomRequestHandler::getParam($request,"merchant"),
            CustomRequestHandler::getParam($request,"key"));
            
        if($verifyMerchant==false)
        {
            $responseMessage = "invalid merchant";
            return $this->customResponse->is400Response($response,$responseMessage);
        } else {
            $merchant = $this->merchant->where(["merchant"=>CustomRequestHandler::getParam($request,"merchant")])->first();
            $responseMessage = GenerateTokenController::generateMerchantToken(
                $merchant->id,
                CustomRequestHandler::getParam($request,"merchant")
            );
            $this->merchant->where(["merchant"=>CustomRequestHandler::getParam($request,"merchant")])->update([
                'token' => $responseMessage,
            ]);
            return $this->customResponse->is200Response($response,$responseMessage);
        }
    }
    public function verifyMerchant($merchant, $key)
    {
        $count = $this->merchant->where(["merchant"=>$merchant])->count();
        if($count==0)
        {
            return false;
        }
        // $merchant = $this->merchant->where(["merchant"=>$merchant])->first();
        // $hashedKey = $merchant->key;
        // $verify = password_verify($key,$hashedKey);
        $verify = $this->merchant->where(["merchant"=>$merchant, "key"=>$key])->first();
        if($verify==false)
        {
            return false;
        }
        return true;
    }

    public function hashPassword($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }

    public function EmailExist($email)
    {
        $count = $this->user->where(['email'=>$email])->count();
        if($count==0)
        {
            return false;
        }
        return true;
    }

}