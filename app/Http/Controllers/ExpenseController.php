<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * 支出一覧を表示
     */
    public function index(Request $request)
    {
        // クエリビルダを変数に入れておき、条件を後から追加できるようにする
        // （$queryに条件をどんどん追加していき、最後にpaginate()で実行するイメージ）
        $query = Expense::where('user_id', Auth::id())->with('category');

        // 支払い状況フィルタ
        // filled('is_paid') → URLに?is_paid=... のパラメータが「空でなく」存在するか確認
        if ($request->filled('is_paid')) {
            // is_paid=1 なら支払い済みのみ、is_paid=0 なら未払いのみを絞り込む
            $query->where('is_paid', $request->is_paid);
        }

           
        // 重要度フィルタ
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // カテゴリフィルタ
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

       // キーワード検索（メモの中身をLIKE検索）
        if ($request->filled('keyword')) {
            // like演算子は「部分一致検索」。%は「任意の文字列」を意味するワイルドカード
            // '%キーワード%' と書くことで「メモの中にキーワードが含まれていればヒット」という検索になる
            $query->where('memo', 'like', '%' . $request->keyword . '%');
        }

       // 期間フィルタ（開始日）
        // whereDate()は日付カラムを「日付部分だけ」で比較するためのメソッド
        // >= で「date_from以降」の支出だけに絞り込む
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        // 期間フィルタ（終了日）
        // <= で「date_to以前」の支出だけに絞り込む
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->latest('date')->paginate(20)->withQueryString();

        // フィルタのプルダウンに使うカテゴリ一覧も一緒に渡す
        $categories = Category::all();

        return view('expenses.index', compact('expenses', 'categories'));
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
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // ファイルが選択されている場合のみ、画像を保存する処理を行う
        // hasFile()：フォームに実際にファイルが添付されて送信されたかどうかを確認する
        if ($request->hasFile('receipt_image')) {
            // store('receipts', 'public')：
            // 「receipts」フォルダの中に、「local」ディスク（storage/app/local/）を使って保存する
            // 戻り値は「receipts/xxxxxxxx.jpg」のような、自動生成されたファイル名を含むパス
            $data['receipt_image'] = $request->file('receipt_image')->store('receipts', 'local');
        }

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

    /**
     * 編集フォームを表示
     */
    public function edit(Expense $expense)
    {
        // Policyでチェック：本人の支出データでなければ403エラー
        // $this->authorize('update', $expense) は、
        // 内部的に ExpensePolicy の update() メソッドを呼び出して判定している
        $this->authorize('update', $expense);

        // 編集フォームでもカテゴリの選択肢が必要なので取得しておく
        $categories = Category::all();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * 支出データを更新
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $data = $request->validated();

        $data['is_paid'] = $request->has('is_paid');

        // 新しい画像が選択された場合のみ、画像の差し替え処理を行う
        if ($request->hasFile('receipt_image')) {
            // 既に古い画像が登録されていれば、ストレージから削除する
            // （放置すると、使われない画像ファイルがどんどん溜まってしまうため）
            if ($expense->receipt_image) {
                Storage::disk('local')->delete($expense->receipt_image);
            }

            // 新しい画像を保存し、そのパスを$dataにセットする
            $data['receipt_image'] = $request->file('receipt_image')->store('receipts', 'local');
        }

        $expense->update($data);

        return redirect()->route('expenses.show', $expense)
            ->with('success', '支出を更新しました！');
    }

    /**
     * 支出データを削除
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        // 支出データを削除する前に、紐づいているレシート画像があればストレージから削除する
        if ($expense->receipt_image) {
            Storage::disk('local')->delete($expense->receipt_image);
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', '支出を削除しました');
    }

    /**
     * レシート画像を表示する
     * 画像ファイルを直接公開URLで配信するのではなく、
     * このメソッドを経由することで「本人のデータかどうか」を確認してから画像を返す
     */
    public function receiptImage(Expense $expense)
    {
        // Policyでチェック：本人の支出データでなければ403エラー
        $this->authorize('view', $expense);

        // 画像が登録されていない場合は404エラー
        abort_if(!$expense->receipt_image, 404);

        // Storage::disk('local')->response()：
        // 非公開ディスク(storage/app/private/)に保存されている画像データを、
        // ブラウザが画像として表示できる形式のレスポンスとして返す
        return Storage::disk('local')->response($expense->receipt_image);
    }
}
    
