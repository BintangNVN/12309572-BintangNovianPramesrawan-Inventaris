<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\LendingController;
use App\Exports\ItemsExport;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('landing');
});

//  LOGIN
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

//  DASHBOARD GLOBAL (BIAR GA ERROR)
Route::middleware('auth')->get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif (auth()->user()->role === 'staff') {
        return redirect('/staff/dashboard');
    }

    return redirect('/');
})->name('dashboard');

// ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

//  STAFF
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');
});

// Category Page
Route::prefix('categories')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
});

// Item Page
Route::prefix('items')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('items.index');
    Route::get('/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/', [ItemController::class, 'store'])->name('items.store');
    Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::get('/items/export', function () {
    return Excel::download(new ItemsExport, 'items.xlsx');
})->name('items.export');

});
Route::get('/lendings/item/{id}', [LendingController::class, 'showByItem'])
    ->name('lendings.showByItem');

Route::get('/export-lendings', [LendingController::class, 'export'])->name('lendings.export');



// CRUD Admin
Route::prefix('admins')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/export', function () {
        return Excel::download(new UsersExport('admin'), 'admin-accounts.xlsx');
    })->name('users.export');

    Route::get('/', [AdminController::class, 'index'])->name('admins.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::put('/{id}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');
});

// CRUD Operator
Route::prefix('operators')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/export', function () {
        return Excel::download(new UsersExport('staff'), 'operator-accounts.xlsx');
    })->name('operators.export');

    Route::get('/', [OperatorController::class, 'index'])->name('operators.index');
    Route::get('/create', [OperatorController::class, 'create'])->name('operators.create');
    Route::post('/{id}/reset-password', [OperatorController::class, 'resetPassword'])->name('operators.resetPassword');
});
Route::get('/{id}/edit', [OperatorController::class, 'edit'])->name('operators.edit');
Route::put('/{id}', [OperatorController::class, 'update'])->name('operators.update');


Route::prefix('lendings')->middleware(['auth', 'role:staff'])->group(function(){
    Route::get('/', [LendingController::class, 'index'])->name('lendings.index');
    Route::get('/create', [LendingController::class, 'create'])->name('lendings.create');
    Route::post('/', [LendingController::class, 'store'])->name('lendings.store');
    Route::get('/{lending}', [LendingController::class, 'show'])->name('lendings.show');
    Route::get('/{lending}/edit', [LendingController::class, 'edit'])->name('lendings.edit');
    Route::put('/{lending}', [LendingController::class, 'update'])->name('lendings.update');
    Route::delete('/{lending}', [LendingController::class, 'destroy'])->name('lendings.destroy');
});

Route::prefix('items_lendings')->middleware(['auth', 'role:staff'])->group(function(){
    Route::get('/', [ItemController::class, 'item'])->name('items_lendings.index');
});
