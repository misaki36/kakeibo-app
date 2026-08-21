<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    // フォームから一括保存を許可するカラム
    protected $fillable = [
        'user_id',
        'amount',
        'memo',
        'date',
    ];
   /**
     * 属性の型変換設定
     */
    protected $casts = [
        'date' => 'date',
    ];


    // この収入データは、どのユーザーのものか（多対1の関係）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // この収入をお気に入りしているユーザー一覧（多対多、中間テーブルはfavorites）
    public function favoritedByUsers()
    {
        return $this->morphToMany(User::class, 'favoritable', 'favorites')
            ->withTimestamps();
    }
}