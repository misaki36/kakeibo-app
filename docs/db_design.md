# DB設計書：主婦家計簿

## テーブル一覧
- users（ユーザー）
- expenses（支出）
- incomes（収入）
- categories（カテゴリ）
- goals（節約目標）
- family_groups（家族グループ）
- family_members（グループメンバー）

## テーブル定義

### users（ユーザー）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | BIGINT | NO | AUTO | ユーザーID（PK） |
| name | VARCHAR(50) | NO | - | ユーザー名 |
| email | VARCHAR(100) | NO | - | メールアドレス |
| password | VARCHAR(255) | NO | - | パスワード（ハッシュ） |
| created_at | TIMESTAMP | NO | CURRENT | 作成日時 |
| updated_at | TIMESTAMP | NO | CURRENT | 更新日時 |

【インデックス】
- PRIMARY KEY (id)
- UNIQUE KEY (email)

### expenses（支出）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | BIGINT | NO | AUTO | 支出ID（PK） |
| user_id | BIGINT | NO | - | ユーザーID（FK） |
| category_id | BIGINT | YES | NULL | カテゴリID（FK） |
| amount | INT | NO | - | 金額 |
| memo | TEXT | YES | NULL | メモ |
| date | DATE | NO | - | 支出日 |
| created_at | TIMESTAMP | NO | CURRENT | 作成日時 |
| updated_at | TIMESTAMP | NO | CURRENT | 更新日時 |

【インデックス】
- PRIMARY KEY (id)
- INDEX (user_id)
- INDEX (date)

【外部キー】
- user_id REFERENCES users(id) ON DELETE CASCADE
- category_id REFERENCES categories(id) ON DELETE SET NULL

### incomes（収入）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | BIGINT | NO | AUTO | 収入ID（PK） |
| user_id | BIGINT | NO | - | ユーザーID（FK） |
| amount | INT | NO | - | 金額 |
| memo | TEXT | YES | NULL | メモ |
| date | DATE | NO | - | 収入日 |
| created_at | TIMESTAMP | NO | CURRENT | 作成日時 |
| updated_at | TIMESTAMP | NO | CURRENT | 更新日時 |

【インデックス】
- PRIMARY KEY (id)
- INDEX (user_id)
- INDEX (date)

【外部キー】
- user_id REFERENCES users(id) ON DELETE CASCADE

### categories（カテゴリ）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | BIGINT | NO | AUTO | カテゴリID（PK） |
| name | VARCHAR(50) | NO | - | カテゴリ名 |
| created_at | TIMESTAMP | NO | CURRENT | 作成日時 |

【インデックス】
- PRIMARY KEY (id)
- UNIQUE KEY (name)

### goals（節約目標）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | BIGINT | NO | AUTO | 目標ID（PK） |
| user_id | BIGINT | NO | - | ユーザーID（FK） |
| amount | INT | NO | - | 目標金額 |
| month | DATE | NO | - | 対象月 |
| created_at | TIMESTAMP | NO | CURRENT | 作成日時 |
| updated_at | TIMESTAMP | NO | CURRENT | 更新日時 |

【インデックス】
- PRIMARY KEY (id)
- INDEX (user_id)

【外部キー】
- user_id REFERENCES users(id) ON DELETE CASCADE

### family_groups（家族グループ）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | BIGINT | NO | AUTO | グループID（PK） |
| name | VARCHAR(100) | NO | - | グループ名 |
| created_at | TIMESTAMP | NO | CURRENT | 作成日時 |

【インデックス】
- PRIMARY KEY (id)

### family_members（グループメンバー）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | BIGINT | NO | AUTO | メンバーID（PK） |
| family_group_id | BIGINT | NO | - | グループID（FK） |
| user_id | BIGINT | NO | - | ユーザーID（FK） |
| created_at | TIMESTAMP | NO | CURRENT | 作成日時 |

【インデックス】
- PRIMARY KEY (id)
- INDEX (family_group_id)
- INDEX (user_id)

【外部キー】
- family_group_id REFERENCES family_groups(id) ON DELETE CASCADE
- user_id REFERENCES users(id) ON DELETE CASCADE

## ER図

```
users (1) ----< (N) expenses
users (1) ----< (N) incomes
users (1) ----< (N) goals
users (N) >----< (N) family_groups  ※family_members中間テーブル経由
categories (1) ----< (N) expenses
```