<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BookController as Book;
use App\Http\Controllers\Api\V1\AuthorController as Author;
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

Route::apiResource('books', Book::class);
Route::apiResource('authors', Author::class);
Route::get('books-year', [Book::class, 'booksPerYear']);
Route::get('books-published', [Book::class, 'getPublishedCounts']);
Route::get('author-gender', [Author::class, 'getAuthorGenderCounts']);
