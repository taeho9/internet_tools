#!/bin/bash

# 스크립트에 전달된 인자의 개수 확인
if [ "$#" -eq 0 ]; then
    echo "Usage: $0 filename"
    echo "filename is real filename or all."
    exit 1
fi

# 전달된 호스트명을 변수에 저장
FNAME=$1

# scp 명령어 실행
echo "tools.blogger.pe.kr 서버로 배포를 시작합니다."
if [ $FNAME = "all" ]; then
    scp -i ~rocky/.ssh/blogger_id_rsa -P 4040 /home/rocky/itools/* taeho@blogger.pe.kr:/home/taeho/tools/
    echo "Upload Complete..!"
    ssh -i ~rocky/.ssh/blogger_id_rsa -p 4040 taeho@blogger.pe.kr "sudo cp /home/taeho/tools/* /var/www/tools/"
    echo "Deploy Complete..!"
else
    scp -i ~rocky/.ssh/blogger_id_rsa -P 4040 /home/rocky/itools/$FNAME taeho@blogger.pe.kr:/home/taeho/tools/
    echo "Upload Complete..!"
    ssh -i ~rocky/.ssh/blogger_id_rsa -p 4040 taeho@blogger.pe.kr "sudo cp /home/taeho/tools/$FNAME /var/www/tools/"
    echo "Deploy Complete..!"
fi