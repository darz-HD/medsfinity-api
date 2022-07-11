<?php

namespace App\Controllers;

use App\Models\Merchant;
use App\Response\CustomResponse;
use App\Requests\CustomRequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use App\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class MerchantController
{

    protected $customResponse;

    protected $merchantEntry;

    protected $validator;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();
        $this->merchant = new Merchant;
        $this->validator = new Validator();
    }
    public function index(Request $request, Response $response) {
        
        $headers = $request->getHeaders();
        $jwt = preg_replace('/^Bearer /', '', $headers["authorization"]);
        $adminCheck = $this->merchant->where(["token"=>$jwt, "merchant"=>$_ENV["ADMIN_MERCHANT_NAME"]])->count();

        if($adminCheck == 0) {
            $responseMessage = "Unauthorized to do this action";
            return $this->customResponse->is400Response($response, $responseMessage);
        } else {
            $responseMessage = $this->merchant->get();
            return $this->customResponse->is200Response($response, $responseMessage);
        }

        // $responseMessage = $this->merchant->get();
        // return $this->customResponse->is200Response($response, $responseMessage);
    }

    public function store(Request $request, Response $response)
    {
        // $keyHash = $this->hash(CustomRequestHandler::getParam($request,'key'));
        // $token =$responseMessage = GenerateTokenController::generateToken(
        //     CustomRequestHandler::getParam($request,"merchant")
        // );
        $headers = $request->getHeaders();
        $jwt = preg_replace('/^Bearer /', '', $headers["authorization"]);
        $adminCheck = $this->merchant->where(["token"=>$jwt, "merchant"=>$_ENV["ADMIN_MERCHANT_NAME"]])->count();

        if($adminCheck == 0) {
            $responseMessage = "Unauthorized to do this action";
            return $this->customResponse->is400Response($response, $responseMessage);
        } else {
            $this->validator->validate($request,[
                "merchant"=>v::notEmpty(),
                "key"=>v::notEmpty(),
            ]);
    
            if($this->validator->failed())
           {
               $responseMessage = $this->validator->errors;
               return $this->customResponse->is400Response($response,$responseMessage);
           }
    
            $data = $this->merchant->create([
                'merchant' => CustomRequestHandler::getParam($request, "merchant"),
                // 'key' => $keyHash,
                'key' => CustomRequestHandler::getParam($request, "key"),
                // 'token' => $token,
            ]);
    
            $responseMessage = $data;
            return $this->customResponse->is200Response($response, $responseMessage);

        }
    }
    public function update(Request $request,Response $response,$id)
    {
        $headers = $request->getHeaders();
        $jwt = preg_replace('/^Bearer /', '', $headers["authorization"]);
        $adminCheck = $this->merchant->where(["token"=>$jwt, "merchant"=>$_ENV["ADMIN_MERCHANT_NAME"]])->count();

        if($adminCheck == 0) {
            $responseMessage = "Unauthorized to do this action";
            return $this->customResponse->is400Response($response, $responseMessage);
        } else
        {
            $this->validator->validate($request,[
                "merchant"=>v::notEmpty(),
                "key"=>v::notEmpty(),
            ]);
    
            // $keyHash = $this->hash(CustomRequestHandler::getParam($request,'key'));
    
            if($this->validator->failed())
            {
                $responseMessage = $this->validator->errors;
                return $this->customResponse->is400Response($response,$responseMessage);
            }
    
            $this->merchant->where(["id"=>$id])->update([
                'merchant' => CustomRequestHandler::getParam($request, "merchant"),
                // 'key' => $keyHash,
                'key' => CustomRequestHandler::getParam($request, "key"),
            ]);
    
            $responseMessage = "merchant updated successfully";
            return $this->customResponse->is200Response($response,$responseMessage);
        }
        
    }

    public function delete(Request $request, Response $response,$id)
    {
        $headers = $request->getHeaders();
        $jwt = preg_replace('/^Bearer /', '', $headers["authorization"]);
        $adminCheck = $this->merchant->where(["token"=>$jwt, "merchant"=>$_ENV["ADMIN_MERCHANT_NAME"]])->count();

        if($adminCheck == 0) {
            $responseMessage = "Unauthorized to do this action";
            return $this->customResponse->is400Response($response, $responseMessage);
        } else {
            $this->merchant->where(["id"=>$id])->delete();
            $responseMessage = "merchant deleted successfully";
            return $this->customResponse->is200Response($response,$responseMessage);
        }
        
    }
    public function hash($data)
    {
        return password_hash($data,PASSWORD_DEFAULT);
    }
}