FROM php:8.3-fpm

WORKDIR /var/www/html

# 초기 배포용 소스 복사
COPY /html/ /bootstrap-html/

# 엔트리포인트 스크립트 복사 및 실행 권한 부여
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]