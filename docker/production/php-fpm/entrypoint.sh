#!/bin/bash
set -e

echo "=========================================="
echo "STARTING LARAVEL ENTRYPOINT"
echo "=========================================="
echo "Current time: $(date)"
echo ""

# Проверка .env
if [ ! -f .env ]; then
  echo "❌ ERROR: .env file not found"
  exit 1
fi

# Выводим конфигурацию БД
echo "=== Database Configuration ==="
grep DB_ .env || echo "No DB configuration found"
echo ""

# Просто ждем БД без проверки nc
echo "Waiting for database initialization (15 seconds)..."
sleep 15

echo "=== Running Laravel Commands ==="

# Генерация ключа приложения
echo "1. Generating application key..."
php artisan key:generate --force --no-interaction 2>&1 | sed 's/^/   /'

# Миграции
echo "2. Running migrations..."
php artisan migrate --force --no-interaction 2>&1 | sed 's/^/   /'

# Сиды
echo "3. Running seeders..."
php artisan db:seed --force --no-interaction 2>&1 | sed 's/^/   /'

# Кэширование
echo "4. Caching configuration..."
php artisan config:clear 2>&1 | sed 's/^/   /'
php artisan config:cache 2>&1 | sed 's/^/   /'
php artisan route:cache 2>&1 | sed 's/^/   /'

echo ""
echo "✅ Entrypoint completed successfully!"
echo ""

exec "$@"
