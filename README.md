# LIDO

Bootstrap técnico inicial del MVP de LIDO.

## Estructura

- `backend/`: API Laravel 12 con autenticación Sanctum, multi-tenant simple y seeders demo.
- `frontend/`: panel React + Vite.

## Backend

### Requisitos

- PHP 8.2+
- Composer
- MySQL 8+

### Instalación

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate:fresh --seed
composer run lint
composer test
php artisan serve
```

### Credenciales demo

- `superadmin@lido.test` / `password`
- `admin@lido.test` / `password`
- `recepcion@lido.test` / `password`
- `caja@lido.test` / `password`

### API inicial

Base URL: `http://localhost:8000/api/v1`

#### Auth

- `POST /login`
- `POST /logout`
- `GET /me`

#### Recursos

- `reservations`
- `units`
- `customers`
- `payments`
