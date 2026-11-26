    <?php

    use App\Http\Controllers\Api\AuthController;
    use App\Http\Controllers\Api\WatchlistController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    // Endpoint autentikasi publik (tanpa token)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Endpoint yang dilindungi Laravel Sanctum (wajib membawa Bearer Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [AuthController::class, 'logout']);

        // CRUD Watchlist Studio Ghibli
        Route::get('/watchlists', [WatchlistController::class, 'index']);
        Route::post('/watchlists', [WatchlistController::class, 'store']);
        Route::put('/watchlists/{id}', [WatchlistController::class, 'update']);
        Route::delete('/watchlists/{id}', [WatchlistController::class, 'destroy']);
    }); 