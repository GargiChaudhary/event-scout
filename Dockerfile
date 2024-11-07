# Use the official PHP image with Apache
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo_pgsql pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy the application files
COPY . .

# Install PHP dependencies using Composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache
RUN a2enmod rewrite

# Run Laravel's artisan commands to clear, cache configuration, cache GraphQL schema, and run migrations
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan lighthouse:cache # Caches the GraphQL schema for optimized performance
RUN php artisan migrate --force # Runs migrations in production

# Expose the default port
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]

# Start Laravel's server, specifying host and port for Render
CMD php artisan serve --host=0.0.0.0 --port=80