FROM php:8.0-cli
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-enable mysqli
RUN apt-get install git
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && apt-get upgrade -y
CMD ["php", "-a"]