<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug-deployment', function () {
    $hotPath = public_path('hot');
    $buildPath = public_path('build/manifest.json');
    
    return [
        'app_name' => config('app.name'),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'public_path' => public_path(),
        'hot_file_path' => $hotPath,
        'hot_file_exists' => file_exists($hotPath),
        'hot_file_contents' => file_exists($hotPath) ? file_get_contents($hotPath) : 'N/A',
        'build_manifest_path' => $buildPath,
        'build_manifest_exists' => file_exists($buildPath),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
    ];
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
