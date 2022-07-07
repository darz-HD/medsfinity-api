<?php

namespace App\Controllers;

use App\Models\Doctor;
use App\Response\CustomResponse;
use App\Requests\CustomRequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use App\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class DoctorController
{

    protected $customResponse;

    protected $doctorEntry;

    protected $validator;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();
        $this->doctor = new Doctor;
        $this->validator = new Validator();
    }
    public function index() {
        $responseMessage = $this->doctor->get();
        return $this->customResponse->is200Response($responseMessage);
    }

    public function store(Request $request, Response $response)
    {

        $this->validator->validate($request,[
            "user_id"=>v::notEmpty(),
            "first_name"=>v::notEmpty(),
            "middle_initial"=>v::notEmpty(),
            "last_name"=>v::notEmpty(),
            "email"=>v::notEmpty()->email(),
            "birth_date"=>v::notEmpty(),
            "contact_number"=>v::notEmpty(),
            "specialty"=>v::notEmpty(),
            "experience_year"=>v::notEmpty(),
            "supporting_documents"=>v::notEmpty(),
            "created_at"=>v::notEmpty(),
            "updated_at"=>v::notEmpty()
        ]);

        if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }


        $data = $this->doctor->create([
            'user_id' => CustomRequestHandler::getParam($request, "user_id"),
            'first_name' => CustomRequestHandler::getParam($request, "first_name"),
            'middle_initial' => CustomRequestHandler::getParam($request, "middle_initial"),
            'last_name' => CustomRequestHandler::getParam($request, "last_name"),
            'email' => CustomRequestHandler::getParam($request, "email"),
            'birth_date' => CustomRequestHandler::getParam($request, "birth_date"),
            'contact_number' => CustomRequestHandler::getParam($request, "contact_number"),
            'specialty' => CustomRequestHandler::getParam($request, "specialty"),
            'experience_year' => CustomRequestHandler::getParam($request, "experience_year"),
            'supporting_documents' => CustomRequestHandler::getParam($request, "supporting_documents"),
            'created_at' => CustomRequestHandler::getParam($request, "created_at"),
            'updated_at' => CustomRequestHandler::getParam($request, "updated_at"),
        ]);

        $responseMessage = $data;
        return $this->customResponse->is200Response($response, $responseMessage);
    }
    public function update(Request $request,Response $response,$id)
    {
        $this->validator->validate($request,[
            "first_name"=>v::notEmpty(),
            "middle_initial"=>v::notEmpty(),
            "last_name"=>v::notEmpty(),
            "email"=>v::notEmpty()->email(),
            "birth_date"=>v::notEmpty(),
            "contact_number"=>v::notEmpty(),
            "specialty"=>v::notEmpty(),
            "experience_year"=>v::notEmpty(),
            "supporting_documents"=>v::notEmpty(),
            "created_at"=>v::notEmpty(),
            "updated_at"=>v::notEmpty()
        ]);

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }

        $this->doctor->where(["id"=>$id])->update([
            'first_name' => CustomRequestHandler::getParam($request, "first_name"),
            'middle_initial' => CustomRequestHandler::getParam($request, "middle_initial"),
            'last_name' => CustomRequestHandler::getParam($request, "last_name"),
            'email' => CustomRequestHandler::getParam($request, "email"),
            'birth_date' => CustomRequestHandler::getParam($request, "birth_date"),
            'contact_number' => CustomRequestHandler::getParam($request, "contact_number"),
            'specialty' => CustomRequestHandler::getParam($request, "specialty"),
            'experience_year' => CustomRequestHandler::getParam($request, "experience_year"),
            'supporting_documents' => CustomRequestHandler::getParam($request, "supporting_documents"),
            'created_at' => CustomRequestHandler::getParam($request, "created_at"),
            'updated_at' => CustomRequestHandler::getParam($request, "updated_at"),
        ]);

        $responseMessage = "doctor updated successfully";
        return $this->customResponse->is200Response($response,$responseMessage);

    }

    public function delete(Response $response,$id)
    {
        $this->doctor->where(["id"=>$id])->delete();
        $responseMessage = "doctor deleted successfully";
        return $this->customResponse->is200Response($response,$responseMessage);
    }
}