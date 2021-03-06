<?php

use App\Http\Controllers\CleanTocController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\MacroPracticeController;
use App\Http\Controllers\SelfController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TocController;
use App\Http\Controllers\TryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ErrorController::class, 'handleError']);
Route::get('/toc', [TocController::class, 'index']);
Route::get('/test', [TestController::class,'index']);
Route::get('/self', [SelfController::class,'index']);
Route::get('/try', [TryController::class,'index']);
Route::get('/get-toc', [CleanTocController::class,'getToc']);


Route::get('test-marco', [MacroPracticeController::class, 'index']);
