# Lido MVP

Minimal beach club management MVP with Laravel 12 API + React/Vite frontend.

## Architecture

- **Backend**: Laravel 12, Sanctum token auth, REST APIs under `/api/v1`
- **Frontend**: React + Vite + Tailwind CSS, Axios, React Router, Zustand auth store
- **Data**: MySQL-ready schema with strict `beach_club_id` tenant ownership across domain models
- **Auth/Roles**: `super_admin`, `beach_admin`, `reception`, `cashier`

## API Scope

- Auth: login, me, logout
- CRUD: beach clubs, sectors, units, customers, reservations, payments, rates
- Reservation safeguards: overlap validation + maintenance unit blocking
- Extra endpoints:
  - `GET /api/v1/dashboard/metrics`
  - `GET /api/v1/calendar/occupancy`
  - `GET /api/v1/units/map`

## Demo Data

`php artisan migrate:fresh --seed` creates:
- 1 beach club
- 5 sectors
- 100 units
- demo customers, reservations, payments, rates
- demo users (password: `password`):
  - `superadmin@demo.test`
  - `admin@demo.test`
  - `reception@demo.test`
  - `cashier@demo.test`

## Environment Variables

Backend (`backend/.env`):

- `APP_URL` (e.g. `http://localhost:8000`)
- `FRONTEND_URL` (e.g. `http://localhost:5173`)
- `DB_CONNECTION=mysql`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173`

Frontend (`frontend/.env` optional):

- `VITE_API_BASE_URL=http://localhost:8000/api/v1`

## Install

### Backend

```bash
cd backend
GIT_CONFIG_COUNT=1 GIT_CONFIG_KEY_0=safe.bareRepository GIT_CONFIG_VALUE_0=all composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

### Frontend

```bash
cd frontend
npm install
npm run dev
```

## Quality Commands

### Backend

```bash
cd backend
composer run lint
composer test
```

### Frontend

```bash
cd frontend
npm run lint
npm run build
npm run format:check
```

## Testing Focus

Added feature tests for reservation overlap and maintenance-unit blocking:

- `tests/Feature/ReservationOverlapValidationTest.php`
