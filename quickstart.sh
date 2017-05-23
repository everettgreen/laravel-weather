#!/usr/bin/env bash
echo "Running composer install"
composer install

if [ ! -f ./.env ]; then
    echo "Copying .env.example to .env"
    cp ./.env.example ./.env
    echo "Don't forget to add your OpenWeatherMap API key to your .env file"
fi

echo "Touching ./database/database.sqlite"
touch ./database/database.sqlite

echo "Running php artisan migrate"
php artisan migrate