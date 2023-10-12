## Web dev assignment

### Getting started

```sh
touch database/database.sqlite
cp .env.example .env
npm run build
./vendor/bin/sail up

sail shell
php artisan migrate
php artisan db:seed

# Import records in to our search index
php artisan scout:sync-index-settings
php artisan scout:import App\\Models\\Author
php artisan scout:import App\\Models\\Book
```
