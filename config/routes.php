<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\MerchantController;
use App\Controllers\UserController;
use App\Controllers\DoctorController;
use App\Controllers\PharmacyController;
use App\Controllers\SubscriptionController;


return function (App $app)
{
    $app->group("/user", function(RouteCollectorProxy $group) {
        $group->get("/",[UserController::class,'index']);
        $group->post('/store', [UserController::class,'store']);
        $group->patch("/update/{id}",[UserController::class,'update']);
        $group->delete('/delete/{id}',[UserController::class,'delete']);
    });

    $app->group("/merchant", function(RouteCollectorProxy $group) {
        $group->get("/",[MerchantController::class,'index']);
        $group->post('/store', [MerchantController::class,'store']);
        $group->patch("/update/{id}",[MerchantController::class,'update']);
        $group->delete('/delete/{id}',[MerchantController::class,'delete']);
    });

    $app->group("/doctor", function(RouteCollectorProxy $group) {
        $group->get("/",[DoctorController::class,'index']);
        $group->post('/store', [DoctorController::class,'store']);
        $group->patch("/update/{id}",[DoctorController::class,'update']);
        $group->delete('/delete/{id}',[DoctorController::class,'delete']);
    });

    $app->group("/pharmacy", function(RouteCollectorProxy $group) {
        $group->get("/",[PharmacyController::class,'index']);
        $group->post('/store', [PharmacyController::class,'store']);
        $group->patch("/update/{id}",[PharmacyController::class,'update']);
        $group->delete('/delete/{id}',[PharmacyController::class,'delete']);
    });

    $app->group("/subscription", function(RouteCollectorProxy $group) {
        $group->get("/",[SubscriptionController::class,'index']);
        $group->post('/store', [SubscriptionController::class,'store']);
        $group->patch("/update/{id}",[SubscriptionController::class,'update']);
        $group->delete('/delete/{id}',[SubscriptionController::class,'delete']);
    });

    $app->group("/auth",function($app)
    {
        $app->post("/login",[\App\Controllers\AuthController::class,"Login"]);
        $app->post("/register",[\App\Controllers\AuthController::class,"Register"]);
        $app->post("/token",[\App\Controllers\AuthController::class,"Token"]);
        $app->post("/reset-token",[\App\Controllers\AuthController::class,"ResetToken"]);
        $app->post("/admin-token",[\App\Controllers\AuthController::class,"AdminToken"]);
    });
};