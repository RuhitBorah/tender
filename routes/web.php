<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Tenders API
    Route::apiResource('tenders', \App\Http\Controllers\TenderController::class);

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'view'])->name('admin.users');
        Route::get('/api/users', [\App\Http\Controllers\UserController::class, 'index']); // API for grid
        Route::post('/api/users', [\App\Http\Controllers\UserController::class, 'store']);
        Route::put('/api/users/{id}', [\App\Http\Controllers\UserController::class, 'update']);
        Route::delete('/api/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy']);
        Route::post('/api/users/{id}/reject-deletion', [\App\Http\Controllers\UserController::class, 'rejectDeletion']);
        
        Route::get('/api/locations', [\App\Http\Controllers\LocationController::class, 'index']);
        
        // Location Management
        Route::get('/locations', [\App\Http\Controllers\Admin\DistrictController::class, 'index'])->name('admin.locations');
        Route::get('/api/districts', [\App\Http\Controllers\Admin\DistrictController::class, 'getAll']);
        Route::post('/api/districts', [\App\Http\Controllers\Admin\DistrictController::class, 'store']);
        Route::put('/api/districts/{id}', [\App\Http\Controllers\Admin\DistrictController::class, 'update']);
        Route::delete('/api/districts/{id}', [\App\Http\Controllers\Admin\DistrictController::class, 'destroy']);
        
        Route::post('/api/subdistricts', [\App\Http\Controllers\Admin\SubdistrictController::class, 'store']);
        Route::put('/api/subdistricts/{id}', [\App\Http\Controllers\Admin\SubdistrictController::class, 'update']);
        Route::delete('/api/subdistricts/{id}', [\App\Http\Controllers\Admin\SubdistrictController::class, 'destroy']);
        
        // Admin Tenders Management
        Route::get('/tenders', [\App\Http\Controllers\TenderController::class, 'adminIndex'])->name('admin.tenders');
        Route::get('/tenders/create', [\App\Http\Controllers\TenderController::class, 'create'])->name('admin.tenders.create');
        Route::post('/tenders', [\App\Http\Controllers\TenderController::class, 'store'])->name('admin.tenders.store');
        Route::get('/api/tenders', [\App\Http\Controllers\TenderController::class, 'adminGetAll']);
        Route::delete('/api/tenders/{id}', [\App\Http\Controllers\TenderController::class, 'destroy']);
    });
});

Route::get('/{username}/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/{username}/tenders', [App\Http\Controllers\UserTenderController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('user.tenders');

Route::put('/{username}/tenders/{tender}', [App\Http\Controllers\UserTenderController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('user.tenders.update');

Route::get('/debug-tenders', function () {
    $user = Illuminate\Support\Facades\Auth::user();
    if (!$user) return 'Not logged in';
    
    return [
        'user' => $user->load('district', 'subdistrict'),
        'sql' => App\Models\Tender::byUserAccess($user)->toSql(),
        'bindings' => App\Models\Tender::byUserAccess($user)->getBindings(),
        'tenders' => App\Models\Tender::byUserAccess($user)->get()
    ];
})->middleware(['auth']);


require __DIR__.'/auth.php';
