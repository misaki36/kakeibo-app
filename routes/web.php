<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // 支出のCRUD(今週はCreate・Readのみなので、7つ全部ではなく必要な分だけ指定)
    // resource()は index/create/store/show/edit/update/destroy の7ルートを一括生成する
    // ->only()で、今回使う4つ(一覧・新規作成フォーム・保存・詳細)だけに絞る
    Route::resource('expenses', ExpenseController::class)->only(['index', 'create', 'store', 'show']);

    // 収入のCRUD(同様にCreate・Readのみ)
    Route::resource('incomes', IncomeController::class)->only(['index', 'create', 'store', 'show']);
});

require __DIR__.'/auth.php';
