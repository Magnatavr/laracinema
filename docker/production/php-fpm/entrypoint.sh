#!/bin/bash
set -e

echo "=========================================="
echo "STARTING LARAVEL ENTRYPOINT"
echo "=========================================="
echo "Current time: $(date)"
echo ""

# 1. Установка Composer если нет
if ! command -v composer >/dev/null 2>&1; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# 2. Установка composer зависимостей
echo "Installing/updating composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader 2>&1 | sed 's/^/   /'

# 3. Установка Node.js если нет
if ! command -v node >/dev/null 2>&1; then
    echo "Installing Node.js..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
    apt-get update && apt-get install -y nodejs npm
fi

# 4. Установка npm зависимостей если есть package.json
if [ -f "package.json" ]; then
    echo "Installing npm dependencies..."
    npm install 2>&1 | sed 's/^/   /'
fi

# 5. Проверка .env
if [ ! -f .env ]; then
    echo "❌ ERROR: .env file not found"
    if [ -f .env.example ]; then
        echo "Copying .env.example to .env..."
        cp .env.example .env
    else
        exit 1
    fi
fi

# 6. Выводим конфигурацию БД
echo "=== Database Configuration ==="
grep DB_ .env || echo "No DB configuration found"
echo ""

# 7. Ждем БД
echo "Waiting for database initialization (15 seconds)..."
sleep 15

echo "=== Running Laravel Commands ==="

# 8. Генерация ключа приложения
echo "1. Generating application key..."
php artisan key:generate --force --no-interaction 2>&1 | sed 's/^/   /'

# 9. Миграции
echo "2. Running migrations..."
php artisan migrate --force --no-interaction 2>&1 | sed 's/^/   /'

# 10. Сиды
echo "3. Running seeders..."
php artisan db:seed --force --no-interaction 2>&1 | sed 's/^/   /'

# 11. Кэширование
echo "4. Caching configuration..."
php artisan config:clear 2>&1 | sed 's/^/   /'
php artisan config:cache 2>&1 | sed 's/^/   /'
php artisan route:cache 2>&1 | sed 's/^/   /'

# 12. Установка прав на storage
echo "5. Setting up permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# 13. Создание символической ссылки storage
echo "6. Creating storage link..."
php artisan storage:link --force 2>&1 | sed 's/^/   /' || true

# 14. Запуск Vite dev server в фоне (для разработки)
echo "7. Starting Vite dev server..."
if [ -f "vite.config.js" ] || [ -f "vite.config.ts" ]; then
    echo "   Vite config found, starting dev server..."
    # Запускаем в фоне и сохраняем PID
    npm run dev > /var/log/vite.log 2>&1 &
    VITE_PID=$!
    echo $VITE_PID > /tmp/vite.pid
    echo "   Vite dev server started (PID: $VITE_PID)"
    echo "   Logs: /var/log/vite.log"
    echo "   URL: http://localhost:5173"
else
    echo "   No Vite config found, skipping..."
fi

echo ""
echo "✅ Entrypoint completed successfully!"
echo "🌐 Application: http://localhost:8080"
echo "⚡ Vite: http://localhost:5173"
echo ""

exec "$@"
