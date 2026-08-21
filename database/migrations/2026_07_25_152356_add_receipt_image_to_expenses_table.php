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
        Schema::table('expenses', function (Blueprint $table) {
            // レシート画像の保存パスを記録するカラム
            // 実際の画像データそのものではなく、「storage/app/public/receipts/xxxx.jpg」のような
            // ファイルパス（文字列）だけをDBに保存する、というのがLaravelでの一般的なやり方
            // nullable：画像を添付しない支出データもあるため
            $table->string('receipt_image')->nullable()->after('memo');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('receipt_image');
        });
    }
};
