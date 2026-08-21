<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    // フォームから一括で保存を許可するカラムを指定
    // これを書かないと、Laravelのセキュリティ機能（Mass Assignment対策）により保存がブロックされる
    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'memo',
        'receipt_image',  // レシート画像の保存パス
        'date',
        'due_date',  // 支払期日
        'priority',  // 重要度（1〜3）
        'is_paid',   // 支払い済みフラグ
    ];

/**
     * 属性の型変換設定
     * date型のカラムをCarbonインスタンス(日付専用クラス)として自動変換してくれる
     * これにより $expense->date->format('Y-m-d') のような書き方ができるようになる
     */
    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'is_paid' => 'boolean',
        'priority' => 'integer',
    ];

    // この支出データは、どのユーザーのものか（多対1の関係）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // この支出データは、どのカテゴリに属するか（多対1の関係）
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}