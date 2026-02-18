FROM php:8.3-fpm

RUN docker-php-ext-install
RUN apt-get update && apt-get install -y
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY /html/ /bootstrap-html/

# 복사 스크립트를 실행하는 entrypoint 스크립트 추가
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]