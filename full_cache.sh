php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
php bin/console doctrine:cache:clear-metadata
