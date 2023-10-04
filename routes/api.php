<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/get_all_invoices', [\App\Http\Controllers\InvoiceController::class, 'get_all_invoices']);
Route::get('/search_invoice', [\App\Http\Controllers\InvoiceController::class, 'search_invoice']);
Route::get("/create_invoice", [\App\Http\Controllers\InvoiceController::class, 'create_invoice']);
Route::get("/customers", [\App\Http\Controllers\CustomerController::class, 'get_all_customers']);
Route::get("/products", [\App\Http\Controllers\ProductController::class, 'get_all_products']);
Route::post("/add_invoice", [\App\Http\Controllers\InvoiceController::class, 'add_invoice']);
Route::get('/invoice/{id}', [\App\Http\Controllers\InvoiceController::class, 'invoice']);
Route::delete('/invoice/invoice_item/{id}', [\App\Http\Controllers\InvoiceController::class, 'delete_invoice_item']);
Route::post('/invoice/{id}', [\App\Http\Controllers\InvoiceController::class, 'update_invoice']);
Route::delete('/invoice/{id}', [\App\Http\Controllers\InvoiceController::class, 'delete_invoice']);
