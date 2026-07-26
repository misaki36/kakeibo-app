<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // categoriesはupdated_atを持たないテーブル設計のため、
    // Laravelに「updated_atは自動更新しない」ことを伝える
    public $timestamps = false;

    // フォームから一括保存を許可するカラム
    protected $fillable = [
        'name',
    ];

    // このカテゴリに属する支出データ一覧を取得できるようにする（逆方向のリレーション）
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}