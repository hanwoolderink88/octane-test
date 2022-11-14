FROM php:latest

RUN apt update 

RUN apt install -y wget
RUN apt install -y curl
RUN apt install -y build-essential
RUN apt install -y libmcrypt-dev
RUN apt install -y libxml2-dev
RUN apt install -y libpcre3-dev
RUN apt install -y zip unzip libzip-dev
RUN apt install -y autoconf
RUN apt install -y libonig-dev
RUN apt install -y openssl

RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install xml
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install zip
RUN docker-php-ext-install intl
RUN docker-php-ext-install gettext

RUN apt update && apt install -y nodejs

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    php composer-setup.php \
    php -r "unlink('composer-setup.php');" \ 
    mv composer.phar /usr/local/bin/composer

RUN useradd user -u 1000

RUN mkdir /home/user && chown user:user /home/user

USER user

RUN touch ~/.bashrc
RUN echo "alias art='php artisan'" >> ~/.bashrc

WORKDIR /var/www

CMD php artisan octane:start --server=roadrunner --watch --workers=1 --host=0.0.0.0 --rpc-port=6001 --port=80