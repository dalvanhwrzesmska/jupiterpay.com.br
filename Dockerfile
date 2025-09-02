FROM php:8.2-apache

RUN apt-get update -y && apt-get install -y sendmail libpng-dev

RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev 

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash
RUN bash -c "source ~/.nvm/nvm.sh && nvm install 22 && node -v"
RUN bash -c "source ~/.nvm/nvm.sh && node -v"
RUN bash -c "source ~/.nvm/nvm.sh && nvm current"
RUN bash -c "source ~/.nvm/nvm.sh && npm -v"

RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Habilita mod_rewrite e instala extensões MySQL (mysqli, pdo_mysql)
RUN a2enmod rewrite \
 && docker-php-ext-install mysqli pdo pdo_mysql fileinfo gd

# Instala o Composer (opcional, mas recomendado para projetos modernos)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia configuração customizada do Apache
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copia todos os arquivos do projeto para o container
COPY . /var/www/html

# Ajusta permissões (importante para evitar problemas de escrita/leitura)
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html

#RUN bash -c "cd /var/www/html && source ~/.nvm/nvm.sh && npm install && npm run build"
#RUN bash -c "cd /var/www/html/public && mkdir build"
#RUN bash -c "cd /var/www/html/public && cp manifest.json build"