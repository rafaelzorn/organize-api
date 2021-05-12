#!/bin/bash

echo ""
echo "Iniciando instalação"
echo ""

echo ""
echo "=================================================> 0%"
echo ""

echo ""
echo "1) Subindo os containers"
echo ""

docker-compose up -d

echo ""
echo "=================================================> 25%"
echo ""

echo ""
echo "2) Instalando dependências via composer"
echo ""

docker exec organize-api composer install

echo ""
echo "=================================================> 25%"
echo ""

echo ""
echo "3) Executando migrations"
echo ""

docker exec organize-api php artisan migrate

echo ""
echo "=================================================> 25%"
echo ""

echo ""
echo "4) Executando seeders"
echo ""

docker exec organize-api php artisan db:seed

echo ""
echo "=================================================> 100%"
echo ""

echo ""
echo "Instalação concluída"
echo ""
