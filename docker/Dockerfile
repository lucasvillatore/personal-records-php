FROM php:8.3-cli

RUN apt-get update && apt-get install -y libzip-dev default-mysql-client libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY ./ /app

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app/public"]
