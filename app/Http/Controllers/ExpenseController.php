<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Http\Requests\StoreExpenseRequest;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * 支出一覧を表示
     */
    public function index()
    {
        // ログイン中のユーザー自身の支出だけを取得する（他人のデータは見せない）
        // with('category') → カテゴリ情報も一緒に取得する（N+1問題を防ぐため）
        // latest('date') → 支出日が新しい順に並べる
        // paginate(20) → 1ページ20件ずつのページネーション
        $expenses = Expense::where('user_id', Auth::id())
            ->with('category')
            ->latest('date')
            ->paginate(20);

        return view('expenses.index', compact('expenses'));
    }

    /**
     * 新規登録フォームを表示
     */
    public function create()
    {
        // フォームのカテゴリ選択用に、全カテゴリを取得しておく
        $categories = Category::all();

        return view('expenses.create', compact('categories'));
    }

    /**
     * 支出データを保存
     */
    public function store(StoreExpenseRequest $request)
    {
        // バリデーション済みのデータを取得
        $data = $request->validated();

        // 誰の支出かを明示的にセットする
        // （フォームから送られてきたuser_idを信用せず、必ずログイン中の本人のIDを使うのがセキュリティ上重要）
        $data['user_id'] = Auth::id();

        Expense::create($data);

        return redirect()->route('expenses.index')
            ->with('success', '支出を登録しました！');
    }

    /**
     * 支出データの詳細を表示
     */
    public function show(Expense $expense)
    {
        // 自分以外のユーザーの支出データを、URLを直接いじって見られないようにする
        // 一致しなければ403エラー（アクセス禁止）を返す
        abort_if($expense->user_id !== Auth::id(), 403);

        return view('expenses.show', compact('expense'));
    }
}