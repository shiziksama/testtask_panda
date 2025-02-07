FROM php:8.2-fpm-alpine

# Встановлення необхідних бібліотек
RUN apk add --no-cache php-mysqli php-gd php-curl php-mbstring php-xml php-json php-session php-sqlite3 sqlite

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Встановлюємо робочу директорію
WORKDIR /var/www


# Копіюємо весь Laravel-код у контейнер
COPY . .

# Виставляємо права для storage та bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Відкриваємо порт 8000
EXPOSE 8000

# Запускаємо Laravel сервер
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
