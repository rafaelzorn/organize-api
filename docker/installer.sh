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
echo "=================================================> 14%"
echo ""

echo ""
echo "2) Criando arquivo .env"
echo ""

docker exec organize-api cp .env.example .env

echo ""
echo "=================================================> 28%"
echo ""

echo ""
echo "3) Instalando dependências via composer"
echo ""

docker exec organize-api composer install --ignore-platform-req=php

echo ""
echo "=================================================> 42%"
echo ""

echo ""
echo "4) Executando migrations"
echo ""

docker exec organize-api php artisan migrate

echo ""
echo "=================================================> 56%"
echo ""

echo ""
echo "5) Executando seeders"
echo ""

docker exec organize-api php artisan db:seed

echo ""
echo "=================================================> 70%"
echo ""

echo ""
echo "6) Gerando chave secreta JWT"
echo ""

docker exec organize-api php artisan jwt:secret

echo ""
echo "=================================================> 84%"
echo ""

echo ""
echo "7) Rodando testes de integração"
echo ""

docker exec organize-api vendor/bin/phpunit tests/Integration/

echo ""
echo "=================================================> 100%"
echo ""

echo ""
echo "Instalação concluída"
echo ""
