#!/bin/sh
set -e

# 마운트된 /var/www/html 디렉토리가 비어있거나 소스가 없는 경우 부트스트랩 파일 복사
if [ ! -f /var/www/html/index.php ]; then
    echo "Copying bootstrap files to /var/www/html..."
    cp -rn /bootstrap-html/* /var/www/html/ 2>/dev/null || cp -r /bootstrap-html/* /var/www/html/
    chown -R www-data:www-data /var/www/html
fi

echo "Starting php-fpm..."
exec "$@"