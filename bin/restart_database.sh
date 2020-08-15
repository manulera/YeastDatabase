#!/usr/bin/env bash
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
php bin/console doctrine:cache:clear-metadata
php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:update --force
