<?php

use App\Http\Controllers\Auth\RegisteredProviderUserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HowItWorksController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StravaController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/how-it-works', [HowItWorksController::class, 'index'])->name('how_it_works.index');
Route::get('/redirect-strava/{userId}', [StravaController::class, 'redirectStrava'])->name('redirect_strava');
Route::get('/webhook', [StravaController::class, 'getStrava'])->name('get_strava');
Route::post('/webhook', [StravaController::class, 'webhookPostStrava'])->name('post_strava');
Route::get('/webhook/autoupload', [StravaController::class, 'autouploadStrava'])->name('autoupload_strava');

Route::middleware('auth')->group(function () {
    Route::get('/event/{eventId}/upload-url', [EventController::class, 'uploadUrlCreate'])->name('event.upload-url.create');
    Route::get('/event/{eventId}/upload-file', [EventController::class, 'uploadFileCreate'])->name('event.upload-file.create');
    Route::post('/event/{eventId}/upload', [EventController::class, 'uploadStore'])->name('event.upload.store');
    Route::post('/event/{eventId}/upload-url', [EventController::class, 'uploadStoreFromUrl'])->name('event.upload.store.url');
    Route::post('/autodistance-upload', [IndexController::class, 'autodistanceUpload'])->name('autodistance_upload');
    Route::get('/authorize-strava', [StravaController::class, 'authorizeStrava'])->name('authorize_strava');
    Route::get('/result/manage', [ResultController::class, 'manage'])->name('result.manage');
});

Route::get('/event', [EventController::class, 'index'])->name('event.index');
Route::get('/event/{eventId}', [EventController::class, 'show'])->name('event.show');
Route::get('/event/{eventId}/result', [EventController::class, 'resultIndex'])->name('event.result.index');
Route::get('/event/{eventId}/startlist', [EventController::class, 'startlistIndex'])->name('event.startlist.index');

Route::get('/result/{resultId}/map', [ResultController::class, 'resultMap'])->name('result.map');
Route::get('/event/result/{registrationId}', [ResultController::class, 'resultUser'])->name('result.user');

Route::get('/registration/{eventId}', [RegistrationController::class, 'index'])->name('registration.index');
Route::get('/result/{eventId}', [ResultController::class, 'index'])->name('result.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/registration/create/{eventId}', [RegistrationController::class, 'create'])->name('registration.create');

});

Route::controller(RegisteredProviderUserController::class)->group(function () {
    Route::get('auth/{provider}', 'redirectToProvider');
    Route::get('auth/{provider}/callback', 'handleProviderCallback');
    Route::get('register-socialite', 'create')->name('register-socialite');
    Route::post('register-socialite', 'store')->name('register-socialite');
});

require __DIR__.'/auth.php';
