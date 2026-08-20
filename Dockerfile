# ベースとなるイメージを指定
# PHP 8.4のFPM(FastCGI Process Manager)版を使う
# tech_stack.mdでPHP 8.4を選定しているのでバージョンを合わせる
FROM php:8.4-fpm

# コンテナ内での作業ディレクトリを指定
# 以降のコマンドはこのディレクトリを基準に実行される
WORKDIR /var/www/html

# Laravelの動作に必要なシステムパッケージをインストール
# apt-get update: パッケージリストを最新化
# apt-get install -y: 確認なしで以下のパッケージを一括インストール
# libpq-dev: PostgreSQLに接続するための拡張機能をビルドするために必要(本番環境がPostgreSQLのため追加)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev

# PHPの拡張機能(Laravelが必要とする機能)をインストール
# pdo_mysql: MySQLに接続するために必須(ローカル開発環境用)
# pdo_pgsql: PostgreSQLに接続するために必須(Render本番環境用に追加)
# mbstring: 日本語などマルチバイト文字を扱うために必須
# zip, gd: 画像処理やファイル圧縮に必要
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring zip exif pcntl gd

# Composer(PHPのパッケージ管理ツール)をコンテナ内にコピー
# 公式のComposerイメージから直接コピーする方法(高速で確実)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# アプリケーションのコード一式を、コンテナの中にコピーする
# ローカル開発ではDocker Composeがコードを別途マウントするので使われないが、
# Render等の本番環境では、このCOPYがないとアプリの中身が空になってしまうため必須
COPY . .

# デバッグ用: storageフォルダの中身がちゃんとコンテナに入っているか確認するための一時的なコマンド
RUN ls -la storage/framework/

# Composerで依存パッケージ(Laravel本体や各種ライブラリ)をインストールする
# --no-dev: 本番環境では開発用のツールは不要なので除外する
# --optimize-autoloader: 読み込み速度を最適化する(本番環境の定番設定)
# --no-interaction: 途中で質問された場合も自動でスキップする(手動操作なしで完了させるため)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# PHPのアップロード関連の設定を変更する
# upload_max_filesize: 1ファイルあたりの最大アップロードサイズ
# post_max_size: フォーム全体で送信できる最大サイズ(uploadより大きい値にする必要がある)
# echoで設定を書き込んだファイルを、PHPが読み込む設定ディレクトリに配置する
RUN echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/uploads.ini

# コンテナが起動したときに、Laravel標準の簡易サーバーを起動する
# 0.0.0.0で待機することで、コンテナの外からのアクセスを受け付けられるようにする
# $PORTはRenderが自動的に設定してくれる環境変数(ローカルではdocker-compose.ymlの設定が優先される)
CMD ["php-fpm"]