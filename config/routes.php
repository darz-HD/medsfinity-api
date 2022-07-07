<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController;
use App\Controllers\DoctorController;

return function (App $app)
{
    $app->group("/user", function(RouteCollectorProxy $group) {
        $group->get("/",[UserController::class,'index']);
        $group->post('/store', [UserController::class,'store']);
        $group->patch("/update/{id}",[UserController::class,'update']);
        $group->delete('/delete/{id}',[UserController::class,'delete']);
    });

    $app->group("/doctor", function(RouteCollectorProxy $group) {
        $group->get("/",[DoctorController::class,'index']);
        $group->post('/store', [DoctorController::class,'store']);
        $group->patch("/update/{id}",[DoctorController::class,'update']);
        $group->delete('/delete/{id}',[DoctorController::class,'delete']);
    });

    $app->group("/auth",function($app)
    {
       $app->post("/login",[\App\Controllers\AuthController::class,"Login"]);
        $app->post("/register",[\App\Controllers\AuthController::class,"Register"]);
    });
};