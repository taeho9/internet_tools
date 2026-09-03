FROM php:8.3-fpm

# 의존성 패키지(git) 및 PHP 확장(calendar, bcmath) 설치
RUN apt-get update && apt-get install -y git \
    && docker-php-ext-install calendar bcmath \
    && rm -rf /var/lib/apt/lists/*

# OOPS-ORG 음력 라이브러리(Lunar, KASI-Lunar, myException) 설치
RUN mkdir -p /usr/share/pear \
    && git clone https://github.com/OOPS-ORG-PHP/myException.git /tmp/myException \
    && cp /tmp/myException/myException.php /usr/share/pear/ \
    && git clone https://github.com/OOPS-ORG-PHP/Lunar.git /tmp/Lunar \
    && cp -r /tmp/Lunar/Lunar.php /tmp/Lunar/Lunar /usr/share/pear/ \
    && git clone https://github.com/OOPS-ORG-PHP/KASI-Lunar.git /tmp/KASI-Lunar \
    && cp -r /tmp/KASI-Lunar/KASI_Lunar.php /tmp/KASI-Lunar/KASI_Lunar /tmp/KASI-Lunar/data /tmp/KASI-Lunar/KASI-data.txt /usr/share/pear/ \
    && rm -rf /tmp/myException /tmp/Lunar /tmp/KASI-Lunar

# PHP include_path에 /usr/share/pear 등록
RUN echo 'include_path = ".:/usr/share/pear:/usr/local/lib/php"' > /usr/local/etc/php/conf.d/pear.ini

WORKDIR /var/www/html

# 초기 배포용 소스 복사
COPY /html/ /bootstrap-html/

# 엔트리포인트 스크립트 복사 및 실행 권한 부여
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]