FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install system dependencies and SQLite
RUN apt-get update && apt-get install -y \
        libsqlite3-dev \
        sqlite3 \
    && rm -rf /var/lib/apt/lists/*

# Install PHP SQLite extension
RUN docker-php-ext-install pdo pdo_sqlite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Set the document root to the public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Enable .htaccess
RUN sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Run migrations (creates database file)
RUN php /var/www/html/migrations/migrate.php

# Set proper permissions (AFTER migration so www-data owns the db file)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage

# Expose port 80
EXPOSE 80
