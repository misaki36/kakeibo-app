<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * 支出のお気に入り状態を切り替える（Ajax専用）
     */
    public function toggleExpense(Expense $expense)
    {
        // 自分のデータでなければ403エラー（他人の支出を勝手にお気に入りできないようにする）
        abort_if($expense->user_id !== Auth::id(), 403);

        $this->toggle($expense);

        // JSON形式でレスポンスを返す（画面遷移せず、JavaScript側で結果を受け取る）
        return response()->json([
            'favorited' => $expense->favoritedByUsers()->where('user_id', Auth::id())->exists(),
        ]);
    }

    /**
     * 収入のお気に入り状態を切り替える（Ajax専用）
     */
    public function toggleIncome(Income $income)
    {
        abort_if($income->user_id !== Auth::id(), 403);

        $this->toggle($income);

        return response()->json([
            'favorited' => $income->favoritedByUsers()->where('user_id', Auth::id())->exists(),
        ]);
    }

    /**
     * 実際のお気に入り切り替え処理（支出・収入で共通のロジック）
     * $model には Expense または Income のインスタンスが渡ってくる
     */
    private function toggle($model)
    {
        $userId = Auth::id();

        // 既にお気に入り登録されているか確認
        $alreadyFavorited = $model->favoritedByUsers()->where('user_id', $userId)->exists();

        if ($alreadyFavorited) {
            // 登録済みなら解除する
            $model->favoritedByUsers()->detach($userId);
        } else {
            // 未登録なら登録する
            $model->favoritedByUsers()->attach($userId);
        }
    }
}