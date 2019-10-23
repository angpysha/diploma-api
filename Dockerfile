FROM php:7.3.9-apache

# Подключаем модуль Apache Rewrite
RUN cd /etc/apache2/mods-enabled && \
    ln -s ../mods-available/rewrite.load
EXPOSE 8080
EXPOSE 80
# # Устанавливаем требуемые разширения PHP
# # -- GD
RUN apt-get update && \
    apt-get install -y libfreetype6-dev autoconf pkg-config libssl-dev
#     docker-php-ext-configure gd --with-freetype-dir=/usr/include/ && \
#     docker-php-ext-install -j$(nproc) gd
# # -- mysql
# RUN docker-php-ext-install -j$(nproc) mysql pdo_mysql
RUN pecl install mongodb \
    &&  echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongo.ini

# Копируем конфигурацию сервера HTTP
#COPY 000-default.conf /etc/apache2/sites-available/
COPY . /var/www/html 

RUN chmod 777 /var/www/html/assets

RUN chmod 777 /var/www/html/web/assets