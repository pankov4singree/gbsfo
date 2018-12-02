<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'BooksController@getBooksTemplate')->name('home');
Route::namespace('Auth')->group(function(){
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::prefix('categories')->group(function(){
    Route::get('/', 'CategoryController@getCategoriesTemplate')->name('categories');
    Route::get('{name}/{id}', 'CategoryController@getCategoryTemplate')->name('categories.view')->where('id', '[0-9]+');
});

Route::prefix('books')->group(function(){
    Route::get('/', 'BooksController@getBooksTemplate')->name('books');
    Route::get('{name}/{id}', 'BooksController@getBookTemplate')->name('books.view')->where('id', '[0-9]+');
});

Route::prefix('authors')->group(function(){
    Route::get('/', 'AuthorController@getAuthorsTemplate')->name('authors');
    Route::get('{name}/{id}', 'AuthorController@getAuthorTemplate')->name('authors.view')->where('id', '[0-9]+');
});

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/', 'BooksController@getBooksTemplateForAdmin')->name('admin.home');

    Route::prefix('categories')->group(function(){
        Route::get('/', 'CategoryController@getCategoriesTemplateForAdmin')->name('admin.categories');
        Route::get('{id}', 'CategoryController@getCategoryTemplateForAdmin')->name('admin.categories.edit')->where('id', '[0-9]+');
        Route::get('create', 'CategoryController@getCreateCategoryTemplateForAdmin')->name('admin.categories.create');
    });

    Route::prefix('books')->group(function(){
        Route::get('/', 'BooksController@getBooksTemplateForAdmin')->name('admin.books');
        Route::get('{id}', 'BooksController@getBookTemplateForAdmin')->name('admin.books.edit')->where('id', '[0-9]+');
        Route::get('create', 'BooksController@getCreateBookTemplateForAdmin')->name('admin.books.create');
    });

    Route::prefix('authors')->group(function(){
        Route::get('/', 'AuthorController@getAuthorsTemplateForAdmin')->name('admin.authors');
        Route::get('{id}', 'AuthorController@getAuthorTemplateForAdmin')->name('admin.authors.edit')->where('id', '[0-9]+');
        Route::get('create', 'AuthorController@getCreateAuthorTemplateForAdmin')->name('admin.authors.create');
    });

});

Route::prefix('api')->group(function () {
    Route::prefix('categories')->group(function(){
        Route::post('/', 'CategoryController@getCategories')->name('api.categories');
        Route::post('create', 'CategoryController@createCategory')->name('api.categories.create')->middleware('auth');
        Route::put('update', 'CategoryController@updateCategory')->name('api.categories.update')->middleware('auth');
        Route::delete('delete/{id}', 'CategoryController@deleteCategory')->name('api.categories.delete')->where('id', '[0-9]+')->middleware('auth');
    });

    Route::prefix('books')->group(function(){
        Route::post('/', 'BooksController@getBooks')->name('api.books');
        Route::post('create', 'BooksController@createBook')->name('api.books.create')->middleware('auth');
        Route::post('update', 'BooksController@updateBook')->name('api.books.update')->middleware('auth');
        Route::delete('delete/{id}', 'BooksController@deleteBook')->name('api.books.delete')->where('id', '[0-9]+')->middleware('auth');
    });

    Route::prefix('authors')->group(function(){
        Route::post('/', 'AuthorController@getAuthors')->name('api.authors');
        Route::post('create', 'AuthorController@createAuthor')->name('api.authors.create')->middleware('auth');
        Route::put('update', 'AuthorController@updateAuthor')->name('api.authors.update')->middleware('auth');
        Route::delete('delete/{id}', 'AuthorController@deleteAuthor')->name('api.authors.delete')->where('id', '[0-9]+')->middleware('auth');
    });
});