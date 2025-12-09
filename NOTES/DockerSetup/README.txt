git clone [repo]
cd [project]

# Copy and edit .env
cp .env.example .env
# Edit: DB_HOST=mysql, REDIS_HOST=redis, etc.

# Build everything from scratch
docker-compose build
docker-compose up -d

# Install dependencies
docker-compose exec laravel.test composer install
docker-compose exec laravel.test npm install

# Fix permissions
docker-compose exec laravel.test chmod -R 775 storage bootstrap/cache
docker-compose exec laravel.test chown -R www-data:www-data storage bootstrap/cache

# Setup Laravel
docker-compose exec laravel.test php artisan key:generate
docker-compose exec laravel.test php artisan migrate
docker-compose exec laravel.test php artisan optimize
docker-compose exec laravel.test npm run build