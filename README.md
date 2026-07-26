# 主婦家計簿アプリ

## 概要
忙しい主婦が家計を簡単に管理できるWebアプリケーションです。
支出・収入の記録、予算管理、節約目標の設定ができます。

## 機能一覧
- ユーザー登録・ログイン
- 支出・収入の手動入力
- レシート撮影による自動入力
- カテゴリ別の収支管理
- 月次レポート・グラフ表示
- 節約目標の設定と達成率確認
- 家族グループでの共有

## 技術スタック
- **バックエンド**: PHP 8.4 / Laravel 13.x
- **フロントエンド**: Blade / Tailwind CSS / Alpine.js
- **データベース**: MySQL 8.0
- **インフラ**: Docker / Render / AWS S3
- **開発ツール**: Git / GitHub / Claude Code

## 開発環境の起動方法

```bash
# コンテナ起動(PHP + Nginx + MySQL)
docker compose up -d --build

# 依存パッケージのインストール
npm install

# CSS/JSのビルド
npm run build

# マイグレーション実行
docker compose exec app php artisan migrate

# カテゴリの初期データ投入
docker compose exec app php artisan db:seed --class=CategorySeeder
```

ブラウザで `http://localhost:8000` にアクセス。

## ドキュメント
- [要件定義書](docs/requirements.md)
- [DB設計書](docs/db_design.md)
- [API仕様書](docs/api_spec.md)
- [技術選定資料](docs/tech_stack.md)
- [スケジュール・工数見積もり](docs/schedule.md)
- [画面設計（Figma）](https://www.figma.com/design/JOdmdd5Iz91CSVUgHhGaZY/%E4%B8%BB%E5%A9%A6%E5%AE%B6%E8%A8%88%E7%B0%BF_%E8%A8%AD%E8%A8%88?node-id=0-1&t=W0q84b4iKbLLlxZn-1)

## 開発期間
- Week21: 企画・設計
- Week22: 環境構築・認証・基本CRUD
- Week23: 全機能実装・UI
- Week24: デプロイ・仕上げ

## Week22 課題 - 実装スキル①（環境構築・認証・基本CRUD）
### 概要
Week21で作成した企画・設計書をもとに、総合プロジェクトの実装フェーズを開始した。開発環境の構築から認証機能、基本CRUD（Create・Read）までを実装し、実践的なLaravel開発フローを体験しながらプロダクトの基盤を構築した。
### 実装内容
- 環境構築
  - Laravelプロジェクトをセットアップ（PHP 8.4 / Laravel 13.x）
  - Docker環境を構築（PHP-FPM + Nginx + MySQL 8.0の3コンテナ構成）
  - MySQL接続設定・マイグレーション動作確認
- 認証機能
  - Laravel Breezeを導入し、新規登録・ログイン・ログアウト機能を実装
- 基本CRUD（支出・収入）
  - DB設計書に基づき categories・expenses・incomes テーブルを作成
  - モデルにリレーション（User⇔Expense⇔Category など）を定義
  - 支出・収入それぞれのCreate・Read機能を実装（一覧・新規登録・詳細画面）
  - FormRequestによるバリデーション（金額必須、日付必須など）を実装
  - カテゴリはSeederで初期データ（食費・日用品費など8種類）を投入
- セキュリティ対応
  - ログインユーザー本人のデータのみアクセス可能に制限（他人のデータへの直接アクセスは403エラー）
  - Mass Assignment対策として$fillableを明示
- Git/GitHub運用
  - week22/setup-auth-crud ブランチで作業し、随時コミット・プッシュ
### 今後追加予定
- 画像アップロード機能（レシート）
- Update・Delete機能
- 節約目標（goals）・家族グループ共有機能
- 月次レポート・グラフ表示
- Week23〜24で残りの機能実装・UI調整・デプロイを行う