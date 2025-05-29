#!/bin/sh

# Entra no diretório da aplicação
cd /var/www

# Copia .env.example se não existir .env
if [ ! -f .env ]; then
    echo "Arquivo .env não encontrado. Criando a partir do .env.example..."
    cp .env.example .env
fi

# Instala as dependências
composer install

# Gera a chave da aplicação
php artisan key:generate

# Ajusta permissões das pastas necessárias
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# NOVO: Aguarda o banco estar pronto
echo "Aguardando banco de dados estar pronto..."
until php artisan db:show 2>/dev/null; do
    echo "Banco ainda não está pronto... aguardando..."
    sleep 2
done
echo "Banco de dados conectado com sucesso!"

# Executa as migrations
echo "Executando migrations..."
php artisan migrate --seed --force

# Inicia o PHP-FPM
php-fpm
