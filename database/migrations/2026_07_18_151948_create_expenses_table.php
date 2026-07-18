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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id(); // 支出ID（主キー）

        // ユーザーとの関連付け（外部キー）
        // constrained()は自動的に users テーブルの id を参照する設定
        // cascadeOnDelete()は「ユーザーが削除されたら、そのユーザーの支出も自動で削除される」設定
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        // カテゴリとの関連付け（外部キー、NULL許可）
        // nullable()：カテゴリ未選択でも支出を登録できるようにする
        // nullOnDelete()：カテゴリが削除されたら、このカラムはNULLになる（支出データ自体は消えない）
        $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

        $table->integer('amount'); // 金額
        $table->text('memo')->nullable(); // メモ（任意入力）
        $table->date('date'); // 支出日

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
        Schema::dropIfExists('expenses');
    }
};
