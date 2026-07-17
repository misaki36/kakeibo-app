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
- **バックエンド**: PHP 8.3 / Laravel 12.x
- **フロントエンド**: Blade / Tailwind CSS / Alpine.js
- **データベース**: MySQL 8.0
- **インフラ**: Docker / Render / AWS S3
- **開発ツール**: Git / GitHub / Claude Code

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
