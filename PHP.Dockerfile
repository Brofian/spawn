FROM php:fpm

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install php Extensions
#	install pdo and pdo_mysql
RUN docker-php-ext-install pdo pdo_mysql

# Update apt-get
RUN apt-get update && \
	apt-get upgrade -y

# Install git
RUN apt-get install -y git

# Install zip and unzip
RUN apt-get install -y zip
RUN apt-get install -y unzip

# Install vim
RUN apt-get install -y vim