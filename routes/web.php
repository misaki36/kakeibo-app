<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\FavoriteController;

Route::get('/', function () {
    return view('welcome');
});

// クロージャ（無名関数）から、DashboardControllerのindex()メソッドを呼び出す形に変更
// これにより、月別収支データを計算してビューに渡せるようになる
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // 支出のCRUD(Week23でUpdate・Deleteを追加、7ルート全部使うようになった)
    // resource()は index/create/store/show/edit/update/destroy の7ルートを一括生成する
    Route::resource('expenses', ExpenseController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    // レシート画像を表示するための専用ルート
    // 画像ファイルを直接公開URLで配信せず、Policyによる本人確認を経てから画像データを返す
    Route::get('/expenses/{expense}/receipt-image', [ExpenseController::class, 'receiptImage'])
        ->name('expenses.receipt-image');
    // 収入のCRUD(Week23でUpdate・Deleteを追加、7ルート全部使うようになった)
Route::resource('incomes', IncomeController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    // お気に入りの切り替え（Ajax専用ルート、POSTのみ）
    Route::post('/expenses/{expense}/favorite', [FavoriteController::class, 'toggleExpense'])
        ->name('expenses.favorite.toggle');
    Route::post('/incomes/{income}/favorite', [FavoriteController::class, 'toggleIncome'])
        ->name('incomes.favorite.toggle');
});


require __DIR__.'/auth.php';
