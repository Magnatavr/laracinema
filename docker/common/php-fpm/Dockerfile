# Stage 1: Build environment with ALL dependencies
FROM php:8.2-fpm AS builder

# Install ALL system dependencies including Node.js
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    unzip \
    git \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev \
    # Добавляем Node.js и npm
    nodejs \
    npm \
    # Добавляем дополнительные инструменты
    gnupg \
    ca-certificates \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@11.7.0 \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    opcache \
    intl \
    zip \
    bcmath \
    soap \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set the working directory
WORKDIR /var/www

# Copy the entire Laravel application
COPY . /var/www

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

# Install Node.js dependencies and build assets (если нужно)
RUN if [ -f "package.json" ]; then \
    npm ci && \
    npm run build; \
    fi

# Stage 2: Production environment with ALL dependencies
FROM php:8.2-fpm AS production

# Install runtime libraries + Node.js
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libfcgi-bin \
    procps \
    # Добавляем Node.js в production stage
    curl \
    gnupg \
    ca-certificates \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@11.7.0 \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Download and install php-fpm health check script
RUN curl -o /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

# Copy the initialization script
COPY ./docker/production/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy PHP extensions and libraries from the builder stage
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/bin/docker-php-ext-* /usr/local/bin/
COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer

# Copy the application code and ALL dependencies from the build stage
COPY --from=builder /var/www /var/www

# Use production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Enable PHP-FPM status page
RUN sed -i '/\[www\]/a pm.status_path = /status' /usr/local/etc/php-fpm.d/zz-docker.conf

# Добавляем кастомные настройки PHP - ФИКСИРУЕМ СИНТАКСИС
RUN echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "upload_max_filesize = 500M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "post_max_size = 500M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "max_execution_time = 600" >> /usr/local/etc/php/conf.d/custom.ini

# Добавляем дополнительные важные настройки
RUN echo "max_input_time = 600" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "max_input_vars = 3000" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "realpath_cache_size = 4096K" >> /usr/local/etc/php/conf.d/custom.ini

# Set working directory
WORKDIR /var/www

# Ensure correct permissions
RUN chown -R www-data:www-data /var/www

# Switch to non-privileged user
USER www-data

# Change the default command to run the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Expose port 9000
EXPOSE 9000
CMD ["php-fpm"]
