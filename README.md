
# Run this command after cloning:

# -------------- install composer
# composer install

# -------------- copy .env file
# cp .env.example .env

# -------------- also add those constants to .env:
# DB_CONNECTION=mysql
# DB_HOST=gabe_db     
# DB_PORT=3306
# DB_DATABASE=pokeapp ted
# DB_USERNAME=root
# DB_PASSWORD=root  

# -------------- generate key
# php artisan key:generate

# php artisan migrate --seed
# docker rm -f gabe_db
# docker compose up -d