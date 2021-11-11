
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


FROM phpdockerio/php74-fpm:latest
WORKDIR "/application"
COPY --from=build /application/public/build ./public/build

RUN apt-get update; \
    apt-get install php7.4-sqlite3 -y; \
    apt-get -y --no-install-recommends install \
        git; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer self-update 1.9.1

COPY . ./
RUN composer install --no-dev --optimize-autoloader
RUN bin/console cache:clear
RUN php bin/console doctrine:database:create
RUN bash bin/restart_database.sh
RUN chmod ugo+rwx ./var/data.db
RUN composer dump-env prod

