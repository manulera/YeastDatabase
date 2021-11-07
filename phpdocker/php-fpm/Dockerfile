
# FROM node:16-alpine as build

# # set working directory
# WORKDIR /application

# # add `/application/node_modules/.bin` to $PATH
# ENV PATH /application/node_modules/.bin:$PATH

# # install application dependencies
# COPY package.json ./
# COPY yarn.lock ./
# RUN yarn install
# RUN ls
# RUN pwd

# # add application
# COPY . ./

# # start application
# RUN yarn build

FROM phpdockerio/php74-fpm:latest
WORKDIR "/application"

RUN pwd
RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        git; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# ENV COMPOSER_MEMORY_LIMIT=-1
# RUN composer self-update 1.9.1
# RUN composer install

