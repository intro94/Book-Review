<?php

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

Route::get('home', function () {
    return redirect('/');
});

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('authors', function () {
    return view('author-list');
})->name('author.list');

Route::get('books', function () {
    return view('book-list');
})->name('book.list');

Route::get('user', function () {
    return view('user', ['user' => auth()->user()->user]);
})->middleware('auth')->name('user');

Route::get('authors/{id}', 'AuthorController@authorInfo')->name('author.info');
Route::get('books/{id}', 'BookController@bookInfo')->name('book.info');

Route::post('comments/create', 'CommentController@commentCreate')->name('comment.create');
Route::post('comments/delete', 'CommentController@commentDelete')->name('comment.delete');

Route::post('user/update', 'UserController@userUpdate')->middleware('auth')->name('user.update');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.home');
    })->name('home');

    Route::get('authors', function () {
        return view('admin.author-list');
    })->name('author.list');

    Route::get('books', function () {
        return view('admin.book-list');
    })->name('book.list');

    Route::get('authors/new', function () {
        return view('admin.author-new');
    })->name('author.new');

    Route::get('books/new/{author_id?}', function ($author_id = 0) {
        return view('admin.book-new', ['author_id' => $author_id]);
    })->name('book.new');

    Route::get('comments', function () {
        return view('admin.comments-all');
    })->name('comment.list');

    Route::get('users', function () {
        return view('admin.user-list');
    })->name('user.list');

    Route::get('authors/{id}', 'AdminController@authorInfo')->name('author.info');
    Route::get('books/{id}', 'AdminController@bookInfo')->name('book.info');
    Route::get('users/{id}', 'AdminController@userInfo')->name('user.info');

    Route::post('authors/delete', 'AdminController@authorDelete')->name('author.delete');
    Route::post('books/delete', 'AdminController@bookDelete')->name('book.delete');
    Route::post('comments/delete', 'AdminController@commentDelete')->name('comment.delete');

    Route::post('authors/update', 'AdminController@authorUpdate')->name('author.update');
    Route::post('books/update', 'AdminController@bookUpdate')->name('book.update');
    Route::post('users/update', 'AdminController@userUpdate')->name('user.update');

    Route::post('authors/create', 'AdminController@authorCreate')->name('author.create');
    Route::post('books/create', 'AdminController@bookCreate')->name('book.create');

    Route::post('users/unlink', 'AdminController@unlinkSocial')->name('unlink.social');
});

Auth::routes();

Route::get('login/github', 'Auth\SocialController@github')->name('login.github');
Route::get('login/github/callback', 'Auth\SocialController@githubCallback');

Route::get('login/google', 'Auth\SocialController@google')->name('login.google');
Route::get('login/google/callback', 'Auth\SocialController@googleCallback');

Route::post('user/unlink', 'UserController@unlinkSocial')->name('user.unlink.social');
