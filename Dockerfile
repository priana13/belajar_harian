FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxml2-dev \
    locales \
    zip \
    iputils-ping \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libmagickwand-dev \
    nginx \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install dom \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Clear cache
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# For PHP 8.x official images
RUN docker-php-ext-install sockets

# Recreate /var/lib/apt/lists/partial if missing
RUN mkdir -p /var/lib/apt/lists/partial && apt-get update

# Remove default nginx site to avoid conflict with our config
RUN rm -f /etc/nginx/sites-enabled/default

# Copy custom php.ini and supervisord config
COPY ./docker/php.ini /usr/local/etc/php/conf.d/uploads.ini
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Install extensions
# RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

# Copy existing app
COPY . /var/www

RUN chown -R $user:$user /var/www

# Copy existing application directory contents
# COPY ./laravel-app/sidonat_yhc/ /var/www

# Copy existing application directory permissions
# COPY --chown=www:www ./laravel-app/sidonat_yhc/ /var/www

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]