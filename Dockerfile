FROM node:16-alpine as build

# set working directory
WORKDIR /app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# install app dependencies
COPY package.json ./
COPY yarn.lock ./
RUN yarn install
RUN ls
RUN pwd

# add app
COPY . ./

# start app
RUN yarn build

FROM php:7.4-cli

WORKDIR /app

COPY --from=build /app/build ./build

# Required for composer
RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# set working directory
WORKDIR /app

# Copy files
COPY . ./

# Install dependencies - php
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# ENV COMPOSER_MEMORY_LIMIT=-1
# RUN composer self-update 1.9.1
# RUN composer install

COPY package.json ./
COPY yarn.lock ./

# Install dependencies - javascript

RUN apt remove cmdtest
RUN apt remove yarn
RUN apt-get update
RUN apt-get install yarn -y
RUN yarn install
RUN yarn encore dev
# RUN bash bin/restart_database.sh


# add app
COPY . ./