FROM php:8.2-fpm-alpine

RUN apk add --no-cache php-mysqli php-gd php-curl php-mbstring php-xml php-json php-session php-sqlite3 sqlite

# Set the working directory
WORKDIR /var/www/html

# Copy the application code to the container
COPY . /var/www/html

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install

# Expose the application port (adjust if necessary)
EXPOSE 8000

# Command to run the application
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
