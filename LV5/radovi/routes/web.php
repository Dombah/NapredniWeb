<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Language switching route
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Tasks routes - available for all authenticated users
    Route::resource('tasks', TaskController::class);
    
    // Student application routes
    Route::post('/tasks/{task}/apply', [TaskController::class, 'apply'])->name('tasks.apply');
    Route::delete('/tasks/{task}/cancel-application', [TaskController::class, 'cancelApplication'])->name('tasks.cancelApplication');
    
    // Teacher routes for managing applications
    Route::get('/my-applications', [TaskController::class, 'myApplications'])->name('tasks.myApplications');
    Route::post('/applications/{application}/accept', [TaskController::class, 'acceptApplication'])->name('applications.accept');
});

// Admin routes for managing user roles
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserRoleController::class, 'index'])->name('admin.users.index');
    Route::patch('/users/{user}/role', [UserRoleController::class, 'updateRole'])->name('admin.users.updateRole');
});

require __DIR__.'/auth.php';
