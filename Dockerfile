# Use a imagem oficial do PHP com o servidor embutido
FROM php:7.4-apache

# Instale as extensões PHP necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
# Add correct rights for www folder.
RUN chown -R www-data:www-data /var/www/

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Copie os arquivos PHP para o diretório de trabalho no contêiner
COPY . /var/www/html
