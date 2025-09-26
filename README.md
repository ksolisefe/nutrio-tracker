## Nutrio Tracker (Laravel + TALL + Livewire Flux)

This app is built on the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire) preset and uses Livewire Flux UI components. It includes importers to seed USDA Foundation and Branded Foods datasets into the local database.

### Stack and tooling
- TALL preset: see `laravel-frontend-presets/tall` [repo](https://github.com/laravel-frontend-presets/tall).
- Livewire Flux UI (basic + pro). Pro package requires authentication with the vendor and a Composer auth token.
- Laravel Debugbar for local profiling: `barryvdh/laravel-debugbar` [repo](https://github.com/barryvdh/laravel-debugbar).

### Requirements
- PHP 8.2+
- Node 18+ and NPM
- Composer
- SQLite (default) or another DB configured via `.env`

### Getting started
1) Install dependencies
```bash
composer install
npm install
```

2) Environment setup
```bash
cp .env.example .env
php artisan key:generate
# SQLite default; ensure the DB file exists
touch database/database.sqlite
```

3) Flux Pro authentication (if not already configured)
- Pro packages require Composer authentication. Configure your token/credentials (e.g., in `auth.json`) per vendor instructions, then run `composer install` again if needed.

4) Run the app (choose ONE of the following)
- Single command (starts PHP server, queue listener, logs, Vite):
```bash
composer run dev
```
- Manual processes (separate terminals):
```bash
php artisan serve
npm run dev
```
Note: run only one Vite dev server. If two are started, the second will bump to another port.

### USDA datasets and seeding
- Download datasets from the USDA FoodData Central downloads page: [fdc.nal.usda.gov/download-datasets](https://fdc.nal.usda.gov/download-datasets).
- Place the files at:
  - `database/seeders/data/usda_foundation_foods.json`
  - `database/seeders/data/usda_branded_foods.json`

Seed the database (will run the importers automatically via `DatabaseSeeder`):
```bash
php artisan migrate:fresh --seed
```
Or run import commands individually:
```bash
php artisan app:import-usda-data            # Foundation Foods
php artisan app:import-usda-branded-data    # Branded Foods
```

### Development notes
- Blade + Livewire
  - For typical pages: use a Blade wrapper that extends `layouts.base` and embed Livewire with `<livewire:... />`.
  - Livewire views must have a single root element and should not `@extends` a layout.
- Vite HMR
  - `@vite(['resources/css/app.css','resources/js/app.js'])` is included in `layouts.base`.
  - Keep only one Vite server running to avoid port bumping.
- Debugbar
  - Debugbar is intended for local development. You can toggle via `APP_DEBUG=true` and the package config.

### References
- TALL preset: [laravel-frontend-presets/tall](https://github.com/laravel-frontend-presets/tall)
- Laravel Debugbar: [barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)
- USDA datasets: [FoodData Central downloads](https://fdc.nal.usda.gov/download-datasets)
