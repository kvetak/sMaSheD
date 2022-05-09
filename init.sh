#!/bin/bash

#Initialize folders
cd ./storage
mkdir -p app/public/files
mkdir -p framework/sessions
mkdir -p framework/cache/data
mkdir -p framework/views
mkdir -p logs
cd ..
chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
chmod -R 777 storage bootstrap/cache

#Clear Laravel config
composer dump-autoload
#Generate encryption key
php artisan key:generate
#Migrate database
php artisan migrate

#Clear all backend stuff
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan cache:clear

#Compile all blades
php artisan view:cache
#Cache new config
php artisan config:cache

 