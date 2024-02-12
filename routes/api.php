<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
except method will ignore "/customers/{id}/edit" and /customers/create routes.
these are responsible for showing forms to the user, 
but this is an api route so we don't need those routes nor methods
*/

Route::resource('customers', CustomerController::class)->except(
    [
        "create", "edit"
    ]
);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::fallback(function () {
    $controller = app(CustomerController::class);
    $message = $controller->callAction('generateResponse', ["unauthorized request", "failure", 401]);
    return response()->json($message, 401);
});
