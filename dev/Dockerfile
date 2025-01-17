FROM php:fpm
RUN curl -sS https://getcomposer.org/installer | \
  php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update
RUN apt install -y git-all unzip
