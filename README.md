# Medicine Store

A Laravel-based medicine store web application.

## Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- MySQL

## Setup

1. Install PHP dependencies:
	`composer install`
2. Install frontend dependencies:
	`npm install`
3. Copy environment file:
	`copy .env.example .env`
4. Generate app key:
	`php artisan key:generate`
5. Configure database in `.env`, then run migrations:
	`php artisan migrate --seed`
6. Start development servers:
	`php artisan serve`
	`npm run dev`

## Notes For GitHub

- Do not commit `.env`, `vendor`, `node_modules`, `public/build`, or runtime cache/log files.
- This repository's `.gitignore` is already configured for these generated files.
