# Tech stack
- Laravel 12
- PHP 8.4
- Postgres 14
- Redis
- Nodejs 14
- Nuxt.js 2

# Setup Laravel
## Install dependencies
```bash
composer install
```

## Copy .env
```bash
cp .env.example .env
```

## Generate `APP_KEY`
```bash
php artisan key:generate
```

## Generate `JWT_SECRET`
```bash
php artisan jwt:secret
```

## Do the configuration in `.env`

## Run Laravel
```bash
php artisan serve
```
Or just use web server

# Setup Nuxt
## Install dependencies
```bash
yarn
```

## Run Nuxt
```bash
yarn dev
```
Default will run on http://localhost:3000