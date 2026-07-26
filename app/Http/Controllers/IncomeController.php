<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Http\Requests\StoreIncomeRequest;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    /**
     * 収入一覧を表示
     */
    public function index()
    {
        // ログイン中のユーザー自身の収入だけを取得する
        $incomes = Income::where('user_id', Auth::id())
            ->latest('date')
            ->paginate(20);

        return view('incomes.index', compact('incomes'));
    }

    /**
     * 新規登録フォームを表示
     */
    public function create()
    {
        return view('incomes.create');
    }

    /**
     * 収入データを保存
     */
    public function store(StoreIncomeRequest $request)
    {
        $data = $request->validated();

        // 誰の収入かを明示的にセット（本人のIDを強制）
        $data['user_id'] = Auth::id();

        Income::create($data);

        return redirect()->route('incomes.index')
            ->with('success', '収入を登録しました！');
    }

    /**
     * 収入データの詳細を表示
     */
    public function show(Income $income)
    {
        // 自分以外の収入データへのアクセスを防ぐ
        abort_if($income->user_id !== Auth::id(), 403);

        return view('incomes.show', compact('income'));
    }
}