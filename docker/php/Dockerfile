FROM php:8.3-fpm-alpine

# Install dependencies needed for Laravel
RUN apk add --no-cache \
    bash \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# Free up some space
RUN rm -rf /var/cache/apk/*

# Install PHP extensions (for Laravel)
RUN docker-php-ext-install pdo_mysql bcmath opcache pcntl gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Install redis extension
RUN mkdir -p /usr/src/php/ext/redis; \
	curl -fsSL https://pecl.php.net/get/redis --ipv4 | tar xvz -C "/usr/src/php/ext/redis" --strip 1; \
	docker-php-ext-install redis;

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
