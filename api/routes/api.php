<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('task')->group(function() {
    Route::get('/getTasks', [TaskController::class, 'view']);
    Route::post('/addTask', [TaskController::class, 'create']);
    Route::post('/updateTask/{id}', [TaskController::class, 'update']);
    Route::post('/updateTaskStatus/{id}', [TaskController::class, 'update2']);
    Route::delete('/deleteTask/{id}', [TaskController::class, 'destroy']);
});
