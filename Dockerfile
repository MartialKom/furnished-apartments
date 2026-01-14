FROM php:8.2-apache
# Upgrade system packages to address vulnerabilities
RUN apt-get update && apt-get upgrade -y

# Installe les dépendances système nécessaires
RUN apt-get install -y \
    libzip-dev unzip curl git libicu-dev \
    && docker-php-ext-install zip mysqli pdo pdo_mysql intl

# Active les modules Apache nécessaires
RUN a2enmod rewrite

# Installe les extensions PHP requises
RUN apt-get install -y \
    libzip-dev unzip curl git \
    && docker-php-ext-install zip mysqli pdo pdo_mysql

# Installe Composer globalement
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Installe les extensions PHP requises par CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libzip-dev unzip \
    && docker-php-ext-install zip mysqli pdo pdo_mysql

# Copie la configuration PHP personnalisée
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Copie les fichiers de l'application dans le dossier Apache
COPY . /var/www/html/

# Définit les permissions pour le dossier writable
RUN mkdir -p /var/www/html/writable/cache \
    && mkdir -p /var/www/html/writable/logs \
    && mkdir -p /var/www/html/writable/uploads \
    && chown -R www-data:www-data /var/www/html/writable/cache \
    && chown -R www-data:www-data /var/www/html/writable/logs \
    && chown -R www-data:www-data /var/www/html/writable/uploads\
    && chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

# Définit le répertoire de travail
WORKDIR /var/www/html

# Installe les dépendances PHP avec Composer
RUN composer install --ignore-platform-req=ext-intl

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html



COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Expose le port 80
EXPOSE 80

# Lance Apache au démarrage
CMD ["apache2-foreground"]