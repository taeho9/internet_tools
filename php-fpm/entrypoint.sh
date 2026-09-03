#!/bin/sh
set -e

# 이미지 내의 최신 소스코드를 마운트된 /var/www/html 디렉토리에 동기화
echo "Syncing latest application files to /var/www/html..."
cp -rf /bootstrap-html/* /var/www/html/
chown -R www-data:www-data /var/www/html

echo "Starting php-fpm..."
exec "$@"