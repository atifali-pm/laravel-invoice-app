<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/get_all_invoices', [\App\Http\Controllers\InvoiceController::class, 'get_all_invoices']);
Route::get('/search_invoice', [\App\Http\Controllers\InvoiceController::class, 'search_invoice']);
