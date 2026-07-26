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
        'date',
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