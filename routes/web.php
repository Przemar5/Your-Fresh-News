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

// Pages
Route::get('/', 'HomeController@index')->name('home');
Route::get('contact', 'ContactController@index')->name('contact');
Route::post('contact', 'ContactController@send')->name('contact.send');
Route::get('search', 'SearchController@index')->name('search');

// Articles
Route::get('articles', 'ArticleController@index')->name('articles.index');
Route::get('articles/create', 'ArticleController@create')->name('articles.create');
Route::get('articles/{slug}', 'ArticleController@show')->where('slug', '[\w\-]+')->name('articles.show');
Route::post('articles', 'ArticleController@store')->name('articles.store');
Route::get('articles/{slug}/edit', 'ArticleController@edit')->where('slug', '[\w\-]+')->name('articles.edit');
Route::patch('articles/{id}', 'ArticleController@update')->where('id', '\d+')->name('articles.update');
Route::delete('articles/{id}', 'ArticleController@destroy')->where('id', '\d+')->name('articles.destroy');

// Categories
Route::get('categories', 'CategoryController@index')->name('categories.index');
Route::get('categories/{slug}', 'CategoryController@show')->where('slug', '[\w\-]+')->name('categories.show');
Route::post('categories', 'CategoryController@store')->name('categories.store');
Route::get('categories/{slug}/edit', 'CategoryController@edit')->where('slug', '[\w\-]+')->name('categories.edit');
Route::patch('categories/{id}', 'CategoryController@update')->where('id', '\d+')->name('categories.update');
Route::delete('categories/{id}', 'CategoryController@destroy')->where('id', '\d+')->name('categories.destroy');

// Comments
// Route::get('comments', 'CommentController@index')->name('comments.index');
// Route::get('comments/{slug}', 'CommentController@show')->where('slug', '[\w\-]+')->name('comments.show');
Route::get('comments/create/{id}', 'CommentController@create')->where('id', '\d+')->name('comments.create');
Route::post('comments', 'CommentController@store')->name('comments.store');
Route::get('comments/{id}/edit', 'CommentController@edit')->where('id', '\d+')->name('comments.edit');
Route::patch('comments/{id}', 'CommentController@update')->where('id', '\d+')->name('comments.update');
Route::delete('comments/{id}', 'CommentController@destroy')->where('id', '\d+')->name('comments.destroy');

// Profiles
Route::get('users/{id}', 'ProfileController@show')->where('id', '\d+')->name('profiles.show');
Route::get('users/{id}/edit', 'ProfileController@edit')->where('id', '\d+')->name('profiles.edit');
Route::patch('users/{id}/update', 'ProfileController@update')->where('id', '\d+')->name('profiles.update');
Route::post('users/{id}/delete', 'ProfileController@delete')->where('id', '\d+')->name('profiles.delete');
Route::delete('users/{id}', 'ProfileController@destroy')->where('id', '\d+')->name('profiles.destroy');

// Ratings
// Route::get('ratings', 'RatingController@index')->name('ratings.index');
// Route::get('ratings/{id}', 'RatingController@show')->where('id', '\d+')->name('ratings.show');
// Route::post('ratings', 'RatingController@store')->name('ratings.store');
Route::post('ratings/article/{id}', 'RatingController@rateArticle')->where('id', '\d+')->name('ratings.rate.article');
Route::post('ratings/comment/{id}', 'RatingController@rateComment')->where('id', '\d+')->name('ratings.rate.comment');
// Route::get('ratings/{id}/edit', 'RatingController@edit')->where('id', '\d+')->name('ratings.edit');
// Route::patch('tags/{id}', 'TagController@update')->where('id', '\d+')->name('tags.update');
// Route::delete('tags/{id}', 'TagController@destroy')->where('id', '\d+')->name('tags.destroy');

// Tags
Route::get('tags', 'TagController@index')->name('tags.index');
Route::get('tags/{id}', 'TagController@show')->where('id', '\d+')->name('tags.show');
Route::post('tags', 'TagController@store')->name('tags.store');
Route::get('tags/{id}/edit', 'TagController@edit')->where('id', '\d+')->name('tags.edit');
Route::patch('tags/{id}', 'TagController@update')->where('id', '\d+')->name('tags.update');
Route::delete('tags/{id}', 'TagController@destroy')->where('id', '\d+')->name('tags.destroy');


Auth::routes(['verify' => true]);

