<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index']);
Route::get('/products/list', [ProductController::class, 'list']);
Route::post('product/add', [ProductController::class, 'store'])->name('product_add');
Route::put('/product/edit/{index}', [ProductController::class, 'edit'])->name('product_edit');
Route::delete('/product/delete/{index}', [ProductController::class, 'delete'])->name('product_delete');
