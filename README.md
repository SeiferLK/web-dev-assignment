# Web dev assignment

Tech stack:

-   **Laravel 10** latest version with PHP 8.2, using modern PHP language features
-   **SQLite** for simple data persistence
-   **Meilisearch** (Laravel Scout) for a powerful search engine that powers the search page and autocompletions.
-   **Docker** (Laravel Sail) setup that creates a `docker-compose.yml` file and a `sail` shell script to interact with the docker container.
-   Blade templates and components, with Tailwind for styling. Pages are fully server-rendered. Dark mode support.

### Running locally

> Note: make sure you have Node >=18 installed on your device before continuing

```sh
# Create a database.sqlite file
touch database/database.sqlite
cp .env.example .env
npm install
npm run build
./vendor/bin/sail up

# (Optional: alias 'sail' to ./vendor/bin/sail)
alias sail=./vendor/bin/sail

# Attach to docker container shell
./vendor/bin/sail shell

# Generate an application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Import records in to our search index
php artisan scout:sync-index-settings
php artisan scout:import App\\Models\\Author
php artisan scout:import App\\Models\\Book

# Run the test suite
php artisan test
```
