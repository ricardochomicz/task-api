# Use a imagem base do PHP com Apache
FROM php:8.2-apache

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

# Copie os arquivos da aplicação para o contêiner
COPY . .

# Instale as dependências do Composer
RUN composer install --optimize-autoloader --no-dev

# Configure as permissões para o Laravel
RUN chown -R www-data:www-data /var/www/task-api/storage /var/www/task-api/bootstrap/cache
RUN chmod -R 775 /var/www/task-api/storage /var/www/task-api/bootstrap/cache

# Configure o Apache para usar o diretório público do Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/task-api!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilite o módulo de rewrite do Apache
RUN a2enmod rewrite

# Exponha a porta 80
EXPOSE 80

# Comando para iniciar o Apache
CMD ["apache2-foreground"]