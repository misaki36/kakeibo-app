<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();

            // 誰がお気に入りしたか
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // 何をお気に入りしたか（支出 or 収入、どちらにも対応できるようにする）
            // favoritable_type：'App\Models\Expense' または 'App\Models\Income' という文字列が入る
            // favoritable_id：その支出または収入の id が入る
            // morphs('favoritable') と書くだけで、上記2つのカラムを自動生成してくれる
            $table->morphs('favoritable');

            $table->timestamps();

            // 同じユーザーが同じデータを二重にお気に入り登録できないようにする制約
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
