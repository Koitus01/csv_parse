FROM php:8.1-cli

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    vim \
    libzip-dev \
    libonig-dev \
    zlib1g-dev \
    jq


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add www user
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www
RUN chown www . -R

# Change current user to www
USER www