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
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# PHPの拡張機能(Laravelが必要とする機能)をインストール
# pdo_mysql: MySQLに接続するために必須
# mbstring: 日本語などマルチバイト文字を扱うために必須
# zip, gd: 画像処理やファイル圧縮に必要
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# Composer(PHPのパッケージ管理ツール)をコンテナ内にコピー
# 公式のComposerイメージから直接コピーする方法(高速で確実)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHPのアップロード関連の設定を変更する
# upload_max_filesize: 1ファイルあたりの最大アップロードサイズ
# post_max_size: フォーム全体で送信できる最大サイズ(uploadより大きい値にする必要がある)
# echoで設定を書き込んだファイルを、PHPが読み込む設定ディレクトリに配置する
RUN echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/uploads.ini

# コンテナが起動したときにPHP-FPM(PHPの実行プロセス)を起動する
CMD ["php-fpm"]