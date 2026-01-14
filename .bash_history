ps aux | grep php-fpm
exit;
tail -f storage/logs/laravel.log
php -m | grep -i error
php -v
tail -f /var/log/php/error.log 2>/dev/null || exit
ls
php artisan storage:link
rm -f public/storage
php artisan storage:link
ls -la public/
readlink -f public/storage
ls -la storage/app/public/movies/
cd banners
ls -la storage/app/public/movies/banners
exit
# 1. Health check
curl http://localhost:8080/health
# 2. Картинка
curl -I http://localhost:8080/storage/movies/banners/IhKyK1V8kv1yi6UpmhOwpiEZCWA3gc40Qn4xCgTo.jpg
# 3. Главная
curl -v http://localhost:8080 2>&1 | head -50
exit
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache
exit
npm run build
npm run dev
docker-compose exec app bash
rm -f /var/www/storage/app/public
ls -la /var/www/storage/app/
rm -rf /var/www/storage/app/public
mkdir -p /var/www/storage/app/public
php artisan storage:link
php artisan config:show filesystems
cat /var/www/config/filesystems.php
clear
rm -f /var/www/public/storage
ls -la /var/www/public/
php artisan storage:link
ls -la /var/www/public/storage
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
npm run dev
cd /var/www
ls\ 
ls
npm run dev
exit
