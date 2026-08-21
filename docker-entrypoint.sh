#!/bin/sh
# 本番環境でコンテナが起動するたびに実行されるスクリプト
# 1. マイグレーションを実行して、データベースにテーブルを作成・更新する
php artisan migrate --force

# 2. カテゴリの初期データを投入する
# CategorySeederはfirstOrCreateを使っているため、既にデータがあっても重複登録されない
php artisan db:seed --class=CategorySeeder --force

# 3. Laravel標準の簡易サーバーを起動する
# execを付けることで、このスクリプト自体をサーバープロセスに置き換える(お作法として推奨されます)
exec php artisan serve --host=0.0.0.0 --port=$PORT