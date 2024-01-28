# lynk
A laravel implemenation of simple transaction and payment service

Instructions to run the project
The project is configured with Laravel Sail
so if docker is installed

Kindly run the following commands

### Env file for Laravel Sail
-- Copy env.example file to .env file. It has basic project configurations e.g. DB name and host

### To Install Packages
-- composer update

### To run the project
-- ./vendor/bin/sail up
### To create db schema
-- ./vendor/bin/sail artisan migrate
### Seed Database with demo data
-- ./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
