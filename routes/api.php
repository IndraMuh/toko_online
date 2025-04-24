<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["prefix"=>"admin","middleware"=>"role:admin"],function(){
    Route::post('/insertbarang',[BarangController::class,'insertBarang']);
    Route::post('/updatebarang/{id}',[BarangController::class,'updateBarang']);
    Route::delete('/hapusbarang/{id}',[BarangController::class,'hapusBarang']);
    Route::post('/transaksi',[TransController::class,'beliBarang']);
});
Route::get("/getbarang",[BarangController::class,"getBarang"]);
Route::get("/getdetailbarang/{id}",[BarangController::class,"getDetailBarang"]);


Route::post("register_admin",[AuthController::class,"register"]);
Route::get("/get_user",[AuthController::class,"getUser"]);
Route::get("/get_detail_user/{id}",[AuthController::class,"getDetailUser"]);
Route::put("/update_user/{id}",[AuthController::class,"update_user"]);
Route::put("/hapus_user/{id}",[AuthController::class,"hapus_user"]);
Route::post("/login",[AuthController::class,"login"]);
Route::get("/logout",[AuthController::class,"logout"]);