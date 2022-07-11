<?php

namespace App\Controllers;

use App\Models\Pharmacy;
use App\Response\CustomResponse;
use App\Requests\CustomRequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use App\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class PharmacyController
{

    protected $customResponse;

    protected $pharmacyEntry;

    protected $validator;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();
        $this->pharmacy = new Pharmacy;
        $this->validator = new Validator();
    }
    public function index(Response $response) {
        $responseMessage = $this->pharmacy->get();
        return $this->customResponse->is200Response($response, $responseMessage);
    }

    public function store(Request $request, Response $response)
    {

        $this->validator->validate($request,[
            "user_id"=>v::notEmpty(),
            "first_name"=>v::notEmpty(),
            "middle_initial"=>v::notEmpty(),
            "last_name"=>v::notEmpty(),
            "email"=>v::notEmpty()->email(),
            "contact_number"=>v::notEmpty(),
            "pharmacy_name"=>v::notEmpty(),
            "country"=>v::notEmpty(),
            "street_address"=>v::notEmpty(),
            "state"=>v::notEmpty(),
            "city"=>v::notEmpty(),
            "postal_code"=>v::notEmpty(),
            "website_name"=>v::notEmpty(),
            "price_list"=>v::notEmpty(),
        ]);

        if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }


        $data = $this->pharmacy->create([
            'user_id' => CustomRequestHandler::getParam($request, "user_id"),
            'first_name' => CustomRequestHandler::getParam($request, "first_name"),
            'middle_initial' => CustomRequestHandler::getParam($request, "middle_initial"),
            'last_name' => CustomRequestHandler::getParam($request, "last_name"),
            'email' => CustomRequestHandler::getParam($request, "email"),
            'contact_number' => CustomRequestHandler::getParam($request, "contact_number"),
            'pharmacy_name' => CustomRequestHandler::getParam($request, "pharmacy_name"),
            'country' => CustomRequestHandler::getParam($request, "country"),
            'street_address' => CustomRequestHandler::getParam($request, "street_address"),
            'state' => CustomRequestHandler::getParam($request, "state"),
            'city' => CustomRequestHandler::getParam($request, "city"),
            'postal_code' => CustomRequestHandler::getParam($request, "postal_code"),
            'website_name' => CustomRequestHandler::getParam($request, "website_name"),
            'price_list' => CustomRequestHandler::getParam($request, "price_list"),
        ]);

        $responseMessage = $data;
        return $this->customResponse->is200Response($response, $responseMessage);
    }
    public function update(Request $request,Response $response,$id)
    {
        $this->validator->validate($request,[
            "user_id"=>v::notEmpty(),
            "first_name"=>v::notEmpty(),
            "middle_initial"=>v::notEmpty(),
            "last_name"=>v::notEmpty(),
            "email"=>v::notEmpty()->email(),
            "contact_number"=>v::notEmpty(),
            "pharmacy_name"=>v::notEmpty(),
            "country"=>v::notEmpty(),
            "street_address"=>v::notEmpty(),
            "state"=>v::notEmpty(),
            "city"=>v::notEmpty(),
            "postal_code"=>v::notEmpty(),
            "website_name"=>v::notEmpty(),
            "price_list"=>v::notEmpty(),
        ]);

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }

        $this->pharmacy->where(["id"=>$id])->update([
            'user_id' => CustomRequestHandler::getParam($request, "user_id"),
            'first_name' => CustomRequestHandler::getParam($request, "first_name"),
            'middle_initial' => CustomRequestHandler::getParam($request, "middle_initial"),
            'last_name' => CustomRequestHandler::getParam($request, "last_name"),
            'email' => CustomRequestHandler::getParam($request, "email"),
            'contact_number' => CustomRequestHandler::getParam($request, "contact_number"),
            'pharmacy_name' => CustomRequestHandler::getParam($request, "pharmacy_name"),
            'country' => CustomRequestHandler::getParam($request, "country"),
            'street_address' => CustomRequestHandler::getParam($request, "street_address"),
            'state' => CustomRequestHandler::getParam($request, "state"),
            'city' => CustomRequestHandler::getParam($request, "city"),
            'postal_code' => CustomRequestHandler::getParam($request, "postal_code"),
            'website_name' => CustomRequestHandler::getParam($request, "website_name"),
            'price_list' => CustomRequestHandler::getParam($request, "price_list"),
        ]);

        $responseMessage = "pharmacy updated successfully";
        return $this->customResponse->is200Response($response,$responseMessage);

    }

    public function delete(Response $response,$id)
    {
        $this->pharmacy->where(["id"=>$id])->delete();
        $responseMessage = "pharmacy deleted successfully";
        return $this->customResponse->is200Response($response,$responseMessage);
    }
}