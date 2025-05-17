FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    npm \
    nodejs \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev \
    supervisor \
    vim \
    nano \
    default-mysql-client

# Instala extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Instala o Composer (versão 2)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho padrão para Laravel
WORKDIR /var/www/html

# Copia o projeto para dentro do container
COPY . .

# Instala dependências do Laravel em modo preferencial (produção)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Ajusta permissões para storage e bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponha a porta do PHP-FPM (9000)
EXPOSE 9000

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]
