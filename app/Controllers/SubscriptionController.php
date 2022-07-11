<?php

namespace App\Controllers;

use App\Models\Subscription;
use App\Response\CustomResponse;
use App\Requests\CustomRequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use App\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class SubscriptionController
{

    protected $customResponse;

    protected $subscriptionEntry;

    protected $validator;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();
        $this->subscription = new Subscription;
        $this->validator = new Validator();
    }
    public function index(Response $response) {
        $responseMessage = $this->subscription->get();
        return $this->customResponse->is200Response($response, $responseMessage);
    }

    public function store(Request $request, Response $response)
    {

        $this->validator->validate($request,[
            "user_id"=>v::notEmpty(),
            "plan_id"=>v::notEmpty(),
            "status"=>v::notEmpty(),
        ]);

        if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }


        $data = $this->subscription->create([
            'user_id' => CustomRequestHandler::getParam($request, "user_id"),
            'plan_id' => CustomRequestHandler::getParam($request, "plan_id"),
            'status' => CustomRequestHandler::getParam($request, "status"),
        ]);

        $responseMessage = $data;
        return $this->customResponse->is200Response($response, $responseMessage);
    }
    public function update(Request $request,Response $response,$id)
    {
        $this->validator->validate($request,[
            "user_id"=>v::notEmpty(),
            "plan_id"=>v::notEmpty(),
            "status"=>v::notEmpty(),
        ]);

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }

        $this->subscription->where(["id"=>$id])->update([
            'user_id' => CustomRequestHandler::getParam($request, "user_id"),
            'plan_id' => CustomRequestHandler::getParam($request, "plan_id"),
            'status' => CustomRequestHandler::getParam($request, "status"),
        ]);

        $responseMessage = "subscription updated successfully";
        return $this->customResponse->is200Response($response,$responseMessage);

    }

    public function delete(Response $response,$id)
    {
        $this->subscription->where(["id"=>$id])->delete();
        $responseMessage = "subscription deleted successfully";
        return $this->customResponse->is200Response($response,$responseMessage);
    }
}