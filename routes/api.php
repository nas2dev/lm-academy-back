<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/test-api-endpoint', function (Request $request) {
    return response()->json([
        'message' => 'Hello, Welcome to REST API Architecture world!',
        'Parameters' => $request->all(),
        'Moral' => $request->query('Moral')
    ], 200);
});
