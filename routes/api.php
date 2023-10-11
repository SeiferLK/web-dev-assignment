<?php

use App\Models\Author;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Search by author
Route::get("/authors", function (Request $request) {
    $request->validate([
        "name" => "string"
    ]);

    return Author::where("name", "like", $request->input("name") . "%")->get();
});
