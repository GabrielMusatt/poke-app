# I. Run project - everytime you restart PC; if you already installed the environment (step II. below)

## 1. run docker:
docker compose up -d

## 2. Run the app:
php artisan serve
##

# II. Setup (first-time dependepncies install)

## 1. clone project:
git clone git@github.com:GabrielMusatt/poke-app.git
##

## 2. copy .env file in root
cd poke-app
#
cp .env.example .env
##

## 3. Check .env

## 4. run docker:
docker compose up -d
##

## 5. PHP deps:
composer install
##

## 6. Generate app key:
php artisan key:generate
##

## 7. Make sure Laravel reads fresh config:
php artisan optimize:clear
#
php artisan config:cache
##

## 8. Create DB from Laravel (your custom command):
php artisan db:create
##

## 9. Run schema + seeders:
php artisan migrate --seed
##

## 10. Run the app:
php artisan serve