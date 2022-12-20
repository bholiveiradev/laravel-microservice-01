<?php

use App\Http\Controllers\{
    CompanyController,
    CategoryController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('companies', CompanyController::class);
Route::apiResource('categories', CategoryController::class);

Route::get('/', function () {
    return response()->json(['status' => 'micro 01 is online']);
});
