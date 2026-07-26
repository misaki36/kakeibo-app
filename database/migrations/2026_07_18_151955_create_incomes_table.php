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
    Schema::create('incomes', function (Blueprint $table) {
        $table->id(); // 収入ID（主キー）

        // ユーザーとの関連付け（外部キー）
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->integer('amount'); // 金額
        $table->text('memo')->nullable(); // メモ（任意入力）
        $table->date('date'); // 収入日

        $table->timestamps(); // created_at, updated_at を自動生成

        // 検索を高速化するためのインデックス
        $table->index('user_id');
        $table->index('date');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
