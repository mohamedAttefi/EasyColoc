<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    Mail::raw('Test email from EasyColoc', function ($message) {
        $message->to('attefimohamed0@gmail.com')
                ->subject('Test Email from EasyColoc');
    });

    return "Email sent successfully!";
});

Route::get('/debug-invitations', function () {
    $invitations = \App\Models\Invitation::all();
    $output = "Invitations count: " . $invitations->count() . "<br>";
    
    foreach ($invitations as $inv) {
        $output .= "Token: " . $inv->token . " | Email: " . $inv->email . " | Colocation: " . $inv->colocation_id . " | Expires: " . $inv->expires_at . "<br>";
    }
    
    return $output;
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Colocation routes
    Route::get('/colocations/create', [ColocationController::class, 'create'])->name('colocations.create');
    Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');
    Route::get('/colocations/{colocation}', [ColocationController::class, 'show'])->name('colocations.show');
    Route::get('/colocations/{colocation}/edit', [ColocationController::class, 'edit'])->name('colocations.edit');
    Route::put('/colocations/{colocation}', [ColocationController::class, 'update'])->name('colocations.update');
    Route::delete('/colocations/{colocation}', [ColocationController::class, 'destroy'])->name('colocations.destroy');
    Route::post('/colocations/{colocation}/leave', [ColocationController::class, 'leave'])->name('colocations.leave');
    Route::delete('/colocations/{colocation}/members/{user}', [ColocationController::class, 'removeMember'])->name('colocations.removeMember');

    // Expense routes
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Category routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Invitation routes
    Route::get('/invitations/create/{colocation}', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/invitations/{colocation}', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/invitations/link/{colocation}', [InvitationController::class, 'link'])->name('invitations.link');

    // Payments / settlements
    Route::post('/colocations/{colocation}/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users/{user}/ban', [AdminController::class, 'banUser'])->name('admin.ban-user');
    Route::patch('/admin/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('admin.unban-user');
});

// Public invitation routes (no auth required)
Route::get('/invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');
Route::post('/invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
Route::post('/invitations/{token}/decline', [InvitationController::class, 'decline'])->name('invitations.decline');

require __DIR__.'/auth.php';
