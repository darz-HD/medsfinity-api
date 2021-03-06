<?php

namespace App\Controllers;

use App\Models\User;
use App\Response\CustomResponse;
use App\Requests\CustomRequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use App\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class UserController
{

    protected $customResponse;

    protected $userEntry;

    protected $validator;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();
        $this->user = new User;
        $this->validator = new Validator();
    }
    public function index(Response $response) {
        $responseMessage = $this->user->get();
        return $this->customResponse->is200Response($response,$responseMessage);
    }

    public function store(Request $request, Response $response)
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

        $passwordHash = $this->hashPassword(CustomRequestHandler::getParam($request,'password'));
        $data = $this->user->create([
            'id' => CustomRequestHandler::getParam($request, "id"),
            'first_name' => CustomRequestHandler::getParam($request, "first_name"),
            'last_name' => CustomRequestHandler::getParam($request, "last_name"),
            'email' => CustomRequestHandler::getParam($request, "email"),
            'password' => $passwordHash,
            'status' => CustomRequestHandler::getParam($request, "status"),
        ]);

        $responseMessage = $data;
        return $this->customResponse->is200Response($response, $responseMessage);
    }
    public function update(Request $request,Response $response,$id)
    {
        $this->validator->validate($request,[
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
        $passwordHash = $this->hashPassword(CustomRequestHandler::getParam($request,'password'));
        $this->user->where(["id"=>$id])->update([
            'first_name' => CustomRequestHandler::getParam($request, "first_name"),
            'last_name' => CustomRequestHandler::getParam($request, "last_name"),
            'email' => CustomRequestHandler::getParam($request, "email"),
            'password' => $passwordHash,
            'status' => CustomRequestHandler::getParam($request, "status"),
        ]);

        $responseMessage = "user updated successfully";
        return $this->customResponse->is200Response($response,$responseMessage);

    }

    public function delete(Response $response,$id)
    {
        $this->user->where(["id"=>$id])->delete();
        $responseMessage = "user deleted successfully";
        return $this->customResponse->is200Response($response,$responseMessage);
    }
    public function hashPassword($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }
}