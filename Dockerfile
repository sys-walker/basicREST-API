#SIMPLE API WITH DOCKER
FROM php:7.3-apache


RUN apt-get update && apt-get install -y curl git nano wget
RUN docker-php-ext-install mysqli pdo_mysql pdo
RUN a2enmod rewrite &&  a2enmod headers
RUN sed -ri -e 's/^([ \t]*)(<\/VirtualHost>)/\1\tHeader set Access-Control-Allow-Origin "*"\n\1\2/g' /etc/apache2/sites-available/*.conf
WORKDIR /var/www/html/
COPY apache2.conf /etc/apache2/apache2.conf
COPY web /var/www/html/
EXPOSE 80/tcp
EXPOSE 443/tcp
