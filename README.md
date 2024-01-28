# lynk
A laravel implemenation of simple transaction and payment service

Instructions to run the project
The project is configured with Laravel Sail
so if docker is installed

Kindly run the following commands

-- composer update
To run the project 
-- ./vendor/bin/sail up
To create db schema
-- ./vendor/bin/sail artisan migrate
Seed Database with demo data
-- ./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
