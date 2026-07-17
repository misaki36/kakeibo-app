# API仕様書：主婦家計簿

## エンドポイント一覧

| メソッド | エンドポイント | 説明 | 認証 |
|---------|--------------|------|------|
| POST | /api/register | 会員登録 | 不要 |
| POST | /api/login | ログイン | 不要 |
| POST | /api/logout | ログアウト | 必要 |
| GET | /api/expenses | 支出一覧取得 | 必要 |
| POST | /api/expenses | 支出作成 | 必要 |
| GET | /api/expenses/{id} | 支出詳細取得 | 必要 |
| PUT | /api/expenses/{id} | 支出更新 | 必要 |
| DELETE | /api/expenses/{id} | 支出削除 | 必要 |
| GET | /api/incomes | 収入一覧取得 | 必要 |
| POST | /api/incomes | 収入作成 | 必要 |
| GET | /api/categories | カテゴリ一覧取得 | 必要 |
| GET | /api/goals | 目標取得 | 必要 |
| POST | /api/goals | 目標設定 | 必要 |
| GET | /api/summary | 月別収支サマリー取得 | 必要 |

## API詳細

### 支出一覧取得

【エンドポイント】
GET /api/expenses

【認証】
必要（Bearer Token）

【クエリパラメータ】
- month (string, optional): 対象月（例: 2025-01）
- category_id (integer, optional): カテゴリで絞り込み

【レスポンス例（200 OK）】
{
  "data": [
    {
      "id": 1,
      "amount": 3000,
      "memo": "スーパーで買い物",
      "date": "2025-01-15",
      "category": {
        "id": 1,
        "name": "食費"
      }
    }
  ]
}

### 支出作成

【エンドポイント】
POST /api/expenses

【認証】
必要（Bearer Token）

【リクエストボディ】
{
  "amount": 3000,
  "category_id": 1,
  "memo": "スーパーで買い物",
  "date": "2025-01-15"
}

【バリデーション】
- amount: 必須、整数、1以上
- category_id: 任意、整数
- memo: 任意、200文字以内
- date: 必須、日付形式

【レスポンス例（201 Created）】
{
  "data": {
    "id": 1,
    "amount": 3000,
    "memo": "スーパーで買い物",
    "date": "2025-01-15",
    "category": {
      "id": 1,
      "name": "食費"
    }
  }
}

### 月別収支サマリー取得

【エンドポイント】
GET /api/summary

【認証】
必要（Bearer Token）

【クエリパラメータ】
- month (string, 必須): 対象月（例: 2025-01）

【レスポンス例（200 OK）】
{
  "data": {
    "month": "2025-01",
    "total_income": 150000,
    "total_expense": 80000,
    "balance": 70000,
    "expenses_by_category": [
      {
        "category": "食費",
        "amount": 30000
      },
      {
        "category": "光熱費",
        "amount": 15000
      }
    ]
  }
}