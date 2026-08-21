<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 今月の開始日・終了日を取得する
        // Carbon::now()：現在の日時を扱うためのクラス
        // startOfMonth()：その月の1日、endOfMonth()：その月の最終日を返す
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthEnd = Carbon::now()->endOfMonth();

        // 先月の開始日・終了日を取得する
        // subMonth()：1ヶ月前の日付にずらす
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // 今月の支出合計を計算する
        // whereBetween()：指定した範囲内の日付のデータだけを絞り込む
        // sum()：指定したカラムの合計値を計算する（該当データがなければ0が返る）
        $thisMonthExpense = Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$thisMonthStart, $thisMonthEnd])
            ->sum('amount');

        // 今月の収入合計を計算する
        $thisMonthIncome = Income::where('user_id', Auth::id())
            ->whereBetween('date', [$thisMonthStart, $thisMonthEnd])
            ->sum('amount');

        // 先月の支出合計を計算する
        $lastMonthExpense = Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');

        // 先月の収入合計を計算する
        $lastMonthIncome = Income::where('user_id', Auth::id())
            ->whereBetween('date', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');

        // 今月の収支（収入 - 支出）を計算する
        $thisMonthBalance = $thisMonthIncome - $thisMonthExpense;

        // 先月と今月の支出の差額を計算する（先月比較機能の元になる数値）
        $expenseDiff = $thisMonthExpense - $lastMonthExpense;

        // カテゴリ別の今月の支出集計
        // with('category')：カテゴリ情報も一緒に取得（N+1問題対策）
        // groupBy：category_idごとにグループ化して集計する
        $categoryBreakdown = Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$thisMonthStart, $thisMonthEnd])
            ->with('category')
            ->get()
            ->groupBy(function ($expense) {
                // カテゴリが未設定の場合は「未分類」というグループ名にする
                return $expense->category->name ?? '未分類';
            })
            ->map(function ($group) {
                // 各グループ（カテゴリ）ごとの金額合計を計算する
                return $group->sum('amount');
            });

        return view('dashboard', compact(
            'thisMonthExpense',
            'thisMonthIncome',
            'thisMonthBalance',
            'lastMonthExpense',
            'expenseDiff',
            'categoryBreakdown'
        ));

       

    }
}