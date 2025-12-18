<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SearchHistoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    // Route::delete('/favorites/clear', [FavoriteController::class, 'clearAll'])->name('favorites.clear');
    Route::get('/favorites/check/{type}/{id}', [FavoriteController::class, 'checkFavorite'])->name('favorites.check');

    // User Search
    Route::get('/search/history', [SearchHistoryController::class, 'index'])->name('search.history');
    Route::post('/search/save', [SearchHistoryController::class, 'store'])->name('search.save');

});

