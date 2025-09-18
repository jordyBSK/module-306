FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm \
    postgresql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create startup script
RUN echo '#!/bin/bash\n\
echo "Waiting for database..."\n\
while ! pg_isready -h database -p 5432 -U postgres; do\n\
  sleep 1\n\
done\n\
echo "Database ready!"\n\
\n\
echo "Running database migrations..."\n\
php bin/console doctrine:migrations:migrate --no-interaction\n\
\n\
echo "Loading sample data..."\n\
PGPASSWORD=postgres psql -h database -U postgres -d football_shop -f sample_data.sql || true\n\
\n\
echo "Starting Symfony server..."\n\
php -S 0.0.0.0:8000 -t public/' > /usr/local/bin/startup.sh

RUN chmod +x /usr/local/bin/startup.sh

# Expose port 8000
EXPOSE 8000

# Start application
CMD ["/usr/local/bin/startup.sh"]