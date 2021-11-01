#SIMPLE API WITH DOCKER
FROM php:7.3-apache


RUN apt-get update && apt-get install -y curl git nano wget
RUN docker-php-ext-install mysqli pdo_mysql
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
WORKDIR /var/www/html/
COPY apache2.conf /etc/apache2/apache2.conf
COPY web /var/www/html/
EXPOSE 80/tcp
EXPOSE 443/tcp
