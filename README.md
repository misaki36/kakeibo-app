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

## Week23 課題 - 実装スキル②（CRUD完成・検索・画像アップロード・ダッシュボード）

### 概要
Week22で構築した基盤（認証・基本CRUD）をもとに、支出・収入のUpdate・Delete機能、検索・フィルタリング、レシート画像アップロード、月別収支ダッシュボードまでを実装し、要件定義書のMust要件（MVP）をすべて満たすところまでプロダクトを仕上げた。

### 実装内容

#### CRUD完成（Update・Delete）
- 支出（expenses）・収入（incomes）双方にedit・update・destroyを実装
- LaravelのPolicy（ExpensePolicy・IncomePolicy）を導入し、本人のデータ以外は編集・削除できないよう権限管理を一元化
- チェックボックス項目（支払い済みフラグ）が未送信時にfalseとして扱われない問題に対応（`$request->has()`で明示的に判定）

#### 支出管理の拡張項目
- `due_date`（支払期日）・`priority`（重要度1〜3）・`is_paid`（支払い済みフラグ）をexpensesテーブルに追加
- 一覧・詳細・登録・編集の全画面に反映し、支払い予定の管理ができるように

#### 検索・フィルタリング機能
- 支払い状況（未払い／支払い済み）
- 重要度（1〜3）
- カテゴリ
- キーワード検索（メモの部分一致）
- 期間（開始日〜終了日）
- 上記5種類のフィルタを複数同時に組み合わせ可能（`request()->except()`を活用し、条件を保持したままURLを生成）
- ページネーションと組み合わせても検索条件が維持されるよう`withQueryString()`を使用

#### レシート画像アップロード機能
- `receipt_image`カラムを追加し、支出データに画像を添付可能に
- 新規登録・編集・削除それぞれで画像の保存／差し替え／削除処理を実装
- 一覧画面にサムネイル表示、詳細・編集画面にプレビュー表示を追加
- **セキュリティ対応**：画像を公開ディスク（public）ではなく非公開ディスク（local）に保存し、専用ルート＋Policyによる本人確認を経由してのみ画像を取得できる仕組みに変更（URLを知っていても第三者はアクセス不可）
- Nginx（`client_max_body_size`）・PHP（`upload_max_filesize`／`post_max_size`）双方のアップロード上限を調整し、スマホ撮影サイズの画像にも対応

#### ダッシュボード（月別収支・グラフ）
- 今月の収入・支出・収支サマリーを表示
- 先月との支出比較（増減額を自動計算）
- カテゴリ別支出の円グラフ表示（Chart.jsを導入）
- `DashboardController`を新設し、クロージャで実装されていたdashboardルートをController経由に変更

#### パフォーマンス最適化
- 一覧表示時のEager Loading（`with('category')`）でN+1問題を回避
- 日付カラムに`$casts`を設定し、Carbonインスタンスとして統一的に扱えるように整理

### セキュリティ対応
- 全CRUD操作にPolicyによる権限チェックを追加（本人以外は403）
- レシート画像も本人以外はアクセス不可（要件定義書のセキュリティ要件に対応）
- FormRequestによるバリデーションを更新系にも整備（UpdateExpenseRequest・UpdateIncomeRequest）

### Git/GitHub運用
- `week23/expense-update-delete`ブランチで作業を継続
- 機能単位でこまめにコミット

### 今後追加予定（Week24）
- 本番環境へのデプロイ
- 節約目標（goals）・家族グループ共有機能
- レシート画像のOCR自動読み取り（Should要件）
- PHPUnitによる自動テストの追加
- README・プレゼン資料の最終仕上げ