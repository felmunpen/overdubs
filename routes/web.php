<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ListController;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');//El nombre de la uri es necesario debido a AuthenticatedSessionController->store

Route::get('/show_profile', [UserController::class, 'show_profile'])->name('show_profile');

Route::get('/search', [AlbumController::class, 'search'])->name('search');
Route::get('/search_by_tag', [AlbumController::class, 'search_by_tag'])->name('search_by_tag');
Route::get('/show_album/{id}', [AlbumController::class, 'show_album'])->name('show_album');

Route::get('/insert_album', [AlbumController::class, 'insert_album'])->name('insert_album');
Route::post('/inserted_album', [AlbumController::class, 'inserted_album'])->name('inserted_album');
Route::post('/selected_album', [AlbumController::class, 'selected_album'])->name('selected_album');
Route::get('/edit_album/{id}', [AlbumController::class, 'edit_album'])->name('edit_album');
Route::post('/edited_album', [AlbumController::class, 'edited_album'])->name('edited_album');
Route::get('/delete_album/{id}', [AlbumController::class, 'delete_album'])->name('delete_album');
Route::post('/deleted_album', [AlbumController::class, 'deleted_album'])->name('deleted_album');

Route::post('/send_review', [ReviewController::class, 'send_review'])->name('send_review');
Route::get('/show_review/{id}', [ReviewController::class, 'show_review'])->name('show_review');
Route::get('/delete_review/{id}', [ReviewController::class, 'delete_review'])->name('delete_review');

Route::post('/created_list', [ListController::class, 'created_list'])->name('created_list');
Route::post('/add_to_list', [ListController::class, 'add_to_list'])->name('add_to_list');
Route::get('/remove_from_list/{list_id}/{element_id}', [ListController::class, 'remove_from_list'])->name('remove_from_list');
Route::get('/show_list/{id}', [ListController::class, 'show_list'])->name('show_list');
Route::get('/edit_list/{id}', [ListController::class, 'edit_list'])->name('edit_list');
Route::post('/edited_list', [ListController::class, 'edited_list'])->name('edited_list');
Route::get('/delete_list/{id}', [ListController::class, 'delete_list'])->name('delete_list');

Route::post('/send_message', [MessageController::class, 'send_message'])->name('send_message');
Route::get('/delete_message/{id}', [MessageController::class, 'delete_message'])->name('delete_message');
Route::post('/send_report', [MessageController::class, 'send_report'])->name('send_report');

Route::get('/show_user/{id}', [UserController::class, 'show_user'])->name('show_user');
Route::get('/show_artist/{id}', [ArtistController::class, 'show_artist'])->name('show_artist');
Route::post('/follow_or_unfollow', [UserController::class, 'follow_or_unfollow'])->middleware(['auth', 'notArtist.check'])->name('follow_or_unfollow');

Route::post('/update_bio', [UserController::class, 'update_bio'])->name('update_bio');
Route::post('/update_description', [ArtistController::class, 'update_description'])->name('update_description');
Route::post('/update_info', [ArtistController::class, 'update_info'])->name('update_info');

Route::get('/admin/show_users', [AdminController::class, 'show_users'])->middleware(['auth', 'admin.check'])->name('show_users');
Route::get('/admin/show_albums', [AdminController::class, 'show_albums'])->middleware(['auth', 'admin.check'])->name('show_albums');
Route::get('/admin/show_artists', [AdminController::class, 'show_artists'])->middleware(['auth', 'admin.check'])->name('show_artists');
Route::get('/admin/show_reviews', [AdminController::class, 'show_reviews'])->middleware(['auth', 'admin.check'])->name('show_reviews');
Route::get('/admin/data_report', [AdminController::class, 'data_report'])->middleware(['auth', 'admin.check'])->name('data_report');

Route::get('/admin/users/block/{id}', [AdminController::class, 'block'])->middleware(['auth', 'admin.check'])->name('admin_block');
Route::post('/sent_message', [MessageController::class, 'send'])->name('sent_message');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
