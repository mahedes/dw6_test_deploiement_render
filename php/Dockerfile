# ──────────────────────────────
# Base image
# ──────────────────────────────
FROM php:8.4-apache

# ──────────────────────────────
# Installer dépendances et extensions PHP
# ──────────────────────────────
RUN apt-get update \
    && apt-get install -y \
        build-essential \
        curl \
        zlib1g-dev \
        g++ \
        git \
        libicu-dev \
        zip \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
        libfreetype6-dev \
        libcurl4-openssl-dev \
        pkg-config \
        libssl-dev \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install exif \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# ──────────────────────────────
# Activer modules Apache
# ──────────────────────────────
RUN a2enmod rewrite ssl socache_shmcb

# ──────────────────────────────
# Copier Symfony + Apache config dans l'image
# ──────────────────────────────
# ../appp = dossier Symfony relatif à php/Dockerfile
COPY ../appp /var/www
COPY ../apache/default.conf /etc/apache2/sites-enabled/000-default.conf
COPY ../apache/default-ssl.conf /etc/apache2/sites-enabled/000-default-ssl.conf

# ──────────────────────────────
# Définir le dossier de travail
# ──────────────────────────────
WORKDIR /var/www

# ──────────────────────────────
# Installer Composer
# ──────────────────────────────
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ──────────────────────────────
# Installer Symfony CLI
# ──────────────────────────────
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# ──────────────────────────────
# Exposer le port Apache
# ──────────────────────────────
EXPOSE 80

# ──────────────────────────────
# Laisser Apache démarrer par défaut
# Render n'a pas besoin de Start Command
# ──────────────────────────────
CMD ["apache2-foreground"]
