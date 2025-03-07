# Use a imagem base do PHP com Apache
FROM php:8.2-fpm

# Instale as dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip 

# Limpe o cache do apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instale extensões do PHP necessárias
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instale o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Defina o diretório de trabalho
WORKDIR /var/www/task-api

# Instalar Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copie os arquivos da aplicação para o contêiner
COPY . .

# Configure as permissões para o Laravel
RUN chown -R www-data:www-data /var/www/task-api/storage /var/www/task-api/bootstrap/cache
RUN chmod -R 775 /var/www/task-api/storage /var/www/task-api/bootstrap/cache

# Gerar autoload do Composer
RUN composer dump-autoload

EXPOSE 9000

# Comando para iniciar o Apache
CMD ["php-fpm"]