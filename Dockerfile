FROM php:8.0-apache
WORKDIR /var/www/html

COPY --chmod=777 . /var/www/html/
EXPOSE 80
