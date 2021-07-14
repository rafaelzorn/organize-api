#!/bin/bash

echo ""
echo "Starting installation"
echo ""

echo ""
echo "=================================================> 0%"
echo ""

echo ""
echo "1) Up the containers"
echo ""

docker-compose up -d

echo ""
echo "=================================================> 12%"
echo ""

echo ""
echo "2) Creating file .env"
echo ""

docker exec organize-api cp .env.example .env

echo ""
echo "=================================================> 24%"
echo ""

echo ""
echo "3) Installing dependencies by composer"
echo ""

docker exec organize-api composer install --ignore-platform-req=php

echo ""
echo "=================================================> 36%"
echo ""

echo ""
echo "4) Running migrations"
echo ""

docker exec organize-api php artisan migrate

echo ""
echo "=================================================> 48%"
echo ""

echo ""
echo "5) Running seeders"
echo ""

docker exec organize-api php artisan db:seed

echo ""
echo "=================================================> 60%"
echo ""

echo ""
echo "6) Generating secret key JWT"
echo ""

docker exec organize-api php artisan jwt:secret

echo ""
echo "=================================================> 72%"
echo ""

echo ""
echo "7) Running integration tests"
echo ""

docker exec organize-api vendor/bin/phpunit tests/Integration/ --testdox

echo ""
echo "=================================================> 84%"
echo ""

echo ""
echo "8) Running unit tests"
echo ""

docker exec organize-api vendor/bin/phpunit tests/Unit/ --testdox

echo ""
echo "=================================================> 100%"
echo ""

echo ""
echo "Installation completed"
echo ""
