FROM php:8.1-fpm

ARG user
ARG uid

# install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor

# cacahe clear
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# insall php extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# latest composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# create system user to run Composer and artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# woking directory
WORKDIR /var/www

COPY . .

# copy supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# composer dependencies
RUN composer install --no-scripts --no-autoloader
RUN composer dump-autoload --optimize

# permisions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# exose port 9000 and start php fpm server
EXPOSE 9000
CMD ["/usr/bin/supervisord"]
