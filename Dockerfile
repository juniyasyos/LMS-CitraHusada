FROM php:8.3-cli

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd xml

# Install Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (LTS version 20) and NPM
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Expose port 8000 for Laravel server, and port 5173 for Vite
EXPOSE 8000 5173

# Set default command to run Laravel development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
