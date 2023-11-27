
FROM node:16-alpine as build

# set working directory
WORKDIR /application

# add `/application/node_modules/.bin` to $PATH
ENV PATH /application/node_modules/.bin:$PATH

# install application dependencies
COPY package.json ./
COPY yarn.lock ./
RUN yarn cache clean
RUN yarn install
RUN ls
RUN pwd

# add application
COPY . ./

# start application
RUN yarn build

FROM composer:1.9.1 as vendor

WORKDIR /app

COPY composer.json composer.json
COPY composer.lock composer.lock
ENV COMPOSER_MEMORY_LIMIT=-1

COPY . .
RUN composer install

FROM php:7.4
WORKDIR "/application"
COPY --from=build /application/public/build ./public/build
COPY --from=vendor app/vendor/ ./vendor/

RUN apt-get update
RUN apt-get install -y unzip
RUN rm -rf /var/lib/apt/lists/*

# Install symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash

COPY . ./
RUN ls bin/
RUN php bin/console cache:clear
RUN php bin/console doctrine:database:create
RUN bash bin/restart_database.sh
COPY example.db ./var/data.db
RUN chmod ugo+rwx ./var/data.db
ENV APP_ENV=prod

CMD ["/root/.symfony5/bin/symfony", "server:start"]
