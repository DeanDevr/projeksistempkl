# Stage 1: Builder stage dengan PHP dan Node
FROM php:8.4-cli AS builder

# Install system dependencies dan Node.js 22
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev untuk production)
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-progress

# Copy package files
COPY package.json package-lock.json ./

# Install Node dependencies
RUN npm ci --only=production

# Copy application code
COPY . .

# Build assets dengan Vite
RUN npm run build

# Stage 2: Production image
FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring zip exif gd \
    && apk del --purge *-dev

# Create nginx log directory
RUN mkdir -p /var/log/nginx /var/log/php-fpm

# Create non-root user
RUN adduser -D -u 1000 -g www www

# Set working directory
WORKDIR /var/www/html

# Copy application from builder stage
COPY --from=builder --chown=www:www /app .

# Create storage and bootstrap cache directories dengan permissions yang benar
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache \
    bootstrap/cache \
    && chown -R www:www storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy built assets dari builder
COPY --from=builder --chown=www:www /app/public/build ./public/build

# Generate key jika tidak ada (untuk production)
RUN if [ ! -f .env ]; then \
    cp .env.example .env && \
    php artisan key:generate --force; \
    fi

# Optimize Laravel untuk production
RUN php artisan optimize:clear && \
    php artisan optimize && \
    php artisan view:cache && \
    php artisan event:cache

# Create custom PHP configuration
RUN echo "memory_limit = 256M" > /usr/local/etc/php/conf.d/custom.ini && \
    echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/custom.ini

# Create nginx configuration
RUN echo "events {" > /etc/nginx/nginx.conf && \
    echo "    worker_connections 1024;" >> /etc/nginx/nginx.conf && \
    echo "}" >> /etc/nginx/nginx.conf && \
    echo "" >> /etc/nginx/nginx.conf && \
    echo "http {" >> /etc/nginx/nginx.conf && \
    echo "    include /etc/nginx/mime.types;" >> /etc/nginx/nginx.conf && \
    echo "    default_type application/octet-stream;" >> /etc/nginx/nginx.conf && \
    echo "" >> /etc/nginx/nginx.conf && \
    echo "    server {" >> /etc/nginx/nginx.conf && \
    echo "        listen \$PORT;" >> /etc/nginx/nginx.conf && \
    echo "        server_name localhost;" >> /etc/nginx/nginx.conf && \
    echo "        root /var/www/html/public;" >> /etc/nginx/nginx.conf && \
    echo "        index index.php index.html;" >> /etc/nginx/nginx.conf && \
    echo "" >> /etc/nginx/nginx.conf && \
    echo "        location / {" >> /etc/nginx/nginx.conf && \
    echo "            try_files \$uri \$uri/ /index.php?\$query_string;" >> /etc/nginx/nginx.conf && \
    echo "        }" >> /etc/nginx/nginx.conf && \
    echo "" >> /etc/nginx/nginx.conf && \
    echo "        location ~ \.php\$ {" >> /etc/nginx/nginx.conf && \
    echo "            fastcgi_pass 127.0.0.1:9000;" >> /etc/nginx/nginx.conf && \
    echo "            fastcgi_index index.php;" >> /etc/nginx/nginx.conf && \
    echo "            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;" >> /etc/nginx/nginx.conf && \
    echo "            include fastcgi_params;" >> /etc/nginx/nginx.conf && \
    echo "        }" >> /etc/nginx/nginx.conf && \
    echo "" >> /etc/nginx/nginx.conf && \
    echo "        location ~ /\.ht {" >> /etc/nginx/nginx.conf && \
    echo "            deny all;" >> /etc/nginx/nginx.conf && \
    echo "        }" >> /etc/nginx/nginx.conf && \
    echo "    }" >> /etc/nginx/nginx.conf && \
    echo "}" >> /etc/nginx/nginx.conf

# Create supervisor configuration
RUN echo "[supervisord]" > /etc/supervisor/conf.d/supervisord.conf && \
    echo "nodaemon=true" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "logfile=/var/log/supervisor/supervisord.log" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "pidfile=/var/run/supervisord.pid" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "[program:nginx]" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "command=nginx -g 'daemon off;'" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "autostart=true" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "autorestart=true" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "stdout_logfile=/dev/stdout" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "stdout_logfile_maxbytes=0" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "stderr_logfile=/dev/stderr" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "stderr_logfile_maxbytes=0" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "[program:php-fpm]" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "command=php-fpm" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "autostart=true" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "autorestart=true" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "stdout_logfile=/dev/stdout" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "stdout_logfile_maxbytes=0" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "stderr_logfile=/dev/stderr" >> /etc/supervisor/conf.d/supervisord.conf && \
    echo "stderr_logfile_maxbytes=0" >> /etc/supervisor/conf.d/supervisord.conf

# Health check endpoint
RUN echo "<?php header('Content-Type: application/json'); echo json_encode(['status' => 'ok', 'timestamp' => time()]);" > /var/www/html/public/health.php

# Expose port (Railway akan mengatur PORT)
EXPOSE $PORT

# Switch to non-root user
USER www

# Start application dengan supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]