<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [ProfileController::class,'dashboard'])
->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/adding', [ProfileController::class,'adding'])
->middleware(['auth', 'verified'])->name('adding');
// Route::get('/dashboard', [ProfileController::class,'dashboard'])
// ->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/home', function (){
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::get('/download', [ProfileController::class, 'download'])->name('download');
    Route::post('/addposte', [ProfileController::class, 'add'])->name('addposte');
    Route::post('/addcomment', [ProfileController::class, 'addcomment'])->name('addcomment');
    Route::post('/like/{post_id}', 
    [ProfileController::class, 'like'])->name('like');
    //Route::post('/unlike/{post_id}', [ProfileController::class, 'like'])->name('unlike');
    Route::get('/get-other-likes/{post_id}',[ProfileController::class, 'getOtherLikes'])->name('get-other-likes');
    //Route::match(['get', 'post'], '/unlike/{post_id}', [ProfileController::class, 'unlike'])->name('unlike');
    Route::post('/dislike/{post_id}', [ProfileController::class, 'unlike'])->name('dislike');
    Route::patch('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
?>