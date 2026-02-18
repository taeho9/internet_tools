#!/bin/sh
set -e

echo "Copying html files to /data/tools.blogger.pe.kr..."
cp -r /bootstrap-html/* /data/tools.blogger.pe.kr
echo "Starting php-fpm..."
exec php-fpm