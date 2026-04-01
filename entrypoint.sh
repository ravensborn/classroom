#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi


role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then
    php artisan migrate
    php artisan optimize:clear

    php-fpm -D
    nginx -g "daemon off;"

    elif [ "$role" = "horizon" ]; then

    echo "Running horizon ... "
    php artisan horizon

    elif [ "$role" = "scheduler" ]; then

    echo "Running scheduler ... "
    php artisan schedule:work

    else
        echo "Could not match the container role \"$role\""
        exit 1
    fi
