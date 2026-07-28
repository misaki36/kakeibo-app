<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Http\Requests\StoreIncomeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateIncomeRequest;

class IncomeController extends Controller
{
    /**
     * 収入一覧を表示
     */
    public function index(Request $request)
    {
        $query = Income::where('user_id', Auth::id());

        // キーワード検索（メモの中身をLIKE検索）
        if ($request->filled('keyword')) {
            $query->where('memo', 'like', '%' . $request->keyword . '%');
        }

        // 期間フィルタ（開始日）
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        // 期間フィルタ（終了日）
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $incomes = $query->latest('date')->paginate(20)->withQueryString();

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

/**
     * 編集フォームを表示
     */
    public function edit(Income $income)
    {
        // Policyでチェック：本人の収入データでなければ403エラー
        $this->authorize('update', $income);

        return view('incomes.edit', compact('income'));
    }

    /**
     * 収入データを更新
     */
    public function update(UpdateIncomeRequest $request, Income $income)
    {
        $this->authorize('update', $income);

        $data = $request->validated();

        $income->update($data);

        return redirect()->route('incomes.show', $income)
            ->with('success', '収入を更新しました！');
    }

    /**
     * 収入データを削除
     */
    public function destroy(Income $income)
    {
        $this->authorize('delete', $income);

        $income->delete();

        return redirect()->route('incomes.index')
            ->with('success', '収入を削除しました');
    }

}