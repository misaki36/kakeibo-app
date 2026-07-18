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

    // この収入データは、どのユーザーのものか（多対1の関係）
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}