#!/bin/bash

echo "Aguardando o banco de dados..."
until mysql -h db -u$DB_USERNAME -p$DB_PASSWORD -e 'select 1'; do
  sleep 2
done

echo "Banco de dados disponível. Executando comandos Laravel..."

php artisan migrate --force
php artisan db:seed --force

exec php-fpm
