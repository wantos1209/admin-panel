<?php

use App\Http\Controllers\ApiController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/login', [ApiController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/listsubarea', [ApiController::class, 'listsubarea']);
    Route::post('/pengiriman', [ApiController::class, 'indexpengiriman']);
    Route::post('/pengirimandetail/{pengiriman_id}', [ApiController::class, 'indexpengirimandetail']);
    Route::post('/createpengiriman', [ApiController::class, 'createPengiriman']);
    Route::post('/createdetailpengiriman/{pengiriman_id}', [ApiController::class, 'createDetailPengiriman']);
    Route::post('/getdestinasi/{stt}', [ApiController::class, 'getDestinasi']);
    Route::delete('/pengiriman/{pengiriman_id}/details/{pengirimandetail_id}', [ApiController::class, 'deleteDetail']);

});

