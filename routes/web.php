<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\TopupController;
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

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/game/{game}', [HomeController::class, 'showGame'])->name('game.detail');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User routes (authenticated users)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/profile', [HomeController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [HomeController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/transactions', [HomeController::class, 'transactionHistory'])->name('user.transactions');
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('user.notifications');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('user.notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('user.notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('user.notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('user.notifications.unread-count');
    Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('user.notifications.latest');
    
    // Topup routes
    Route::get('/topup/{game}', [TopupController::class, 'showTopupForm'])->name('topup.form');
    Route::post('/topup/{game}', [TopupController::class, 'processTopup'])->name('topup.process');
    Route::get('/topup/confirmation/{transaction}', [TopupController::class, 'showConfirmation'])->name('topup.confirmation');
    Route::post('/topup/cancel/{transaction}', [TopupController::class, 'cancelTransaction'])->name('topup.cancel');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Users management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Games management
    Route::get('/games', [AdminController::class, 'games'])->name('games');
    Route::get('/games/create', [AdminController::class, 'createGame'])->name('games.create');
    Route::post('/games', [AdminController::class, 'storeGame'])->name('games.store');
    Route::get('/games/{game}/edit', [AdminController::class, 'editGame'])->name('games.edit');
    Route::put('/games/{game}', [AdminController::class, 'updateGame'])->name('games.update');
    Route::delete('/games/{game}', [AdminController::class, 'deleteGame'])->name('games.delete');
    
    // Transactions management
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::put('/transactions/{transaction}/process', [AdminController::class, 'processTransaction'])->name('transactions.process');
});

// Operator routes
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', [OperatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/pending-transactions', [OperatorController::class, 'pendingTransactions'])->name('pending-transactions');
    Route::get('/transactions/{transaction}', [OperatorController::class, 'showTransaction'])->name('transaction.detail');
    Route::put('/transactions/{transaction}/process', [OperatorController::class, 'processTransaction'])->name('transaction.process');
    Route::get('/processed-transactions', [OperatorController::class, 'processedTransactions'])->name('processed-transactions');
});

// Staff routes (admin + operator)
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    // Common staff functionality can be added here
});
