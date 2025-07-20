FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libzip-dev \
    default-mysql-client \
    libonig-dev \
    libxml2-dev \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY composer.json composer.lock /app/

RUN composer install --no-interaction --prefer-dist

COPY . /app

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app/public"]
