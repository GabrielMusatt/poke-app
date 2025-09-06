

# Setup

## clone project:
git clone git@github.com:GabrielMusatt/poke-app.git
##

## copy .env file
cp .env.example .env
##

## Add this to .env file (and delete duplicates):

##### DB_CONNECTION=mysql
##### DB_HOST=127.0.0.1
##### DB_PORT=3307
##### DB_DATABASE=pokeapp
##### DB_USERNAME=root
##### DB_PASSWORD=root
##### CACHE_STORE=file
##### SESSION_DRIVER=file
##### QUEUE_CONNECTION=sync
##

## run docker:
docker compose up -d


## PHP deps:
composer install
##

## Generate app key (first time only)
php artisan key:generate
##

## Make sure Laravel reads fresh config
php artisan optimize:clear
php artisan config:cache
##

## Create DB from Laravel (your custom command)
php artisan db:create
##

## Run schema + seeders
php artisan migrate --seed
##

## Run the app
php artisan serve
##