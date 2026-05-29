# LIDO Beach Club - Inicio Rápido

Sistema completo de gestión para beach clubs con backend Laravel y frontend React.

## Prerequisitos

- PHP 8.2+
- Composer
- Node.js 18+
- npm
- MySQL 8.0+ o PostgreSQL

## Backend (Laravel)

### Instalación

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

### Configurar Base de Datos

Editar `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lido
DB_USERNAME=root
DB_PASSWORD=
```

### Migrar y Sembrar

```bash
php artisan migrate
php artisan db:seed
```

### Iniciar Servidor

```bash
php artisan serve
# Servidor en http://localhost:8000
```

## Frontend (React)

### Instalación

```bash
cd frontend
npm install
```

### Desarrollo

```bash
npm run dev
# Servidor en http://localhost:5173
```

### Build para Producción

```bash
npm run build
```

## Credenciales de Prueba

**Frontend:**
- Email: `admin@lido.com`
- Contraseña: `admin123`

**Backend (si usas seeders):**
- Email: Revisar en `database/seeders`
- Contraseña: Por defecto `password`

## Estructura del Proyecto

```
lido/
├── backend/          # API Laravel
│   ├── app/
│   │   ├── Models/
│   │   ├── Http/
│   │   ├── Services/
│   │   └── DTOs/
│   └── routes/api.php
│
└── frontend/         # App React
    └── src/
        ├── pages/    # Login, Dashboard, Reservations
        ├── components/
        ├── services/ # API clients con mock data
        ├── stores/   # Zustand
        └── types/
```

## URLs

- **Frontend**: http://localhost:5173
- **Backend API**: http://localhost:8000/api/v1
- **API Docs**: http://localhost:8000/api/documentation (si Swagger está configurado)

## Características Implementadas

### Backend
- ✅ Modelos completos (User, BeachClub, Reservation, Customer, etc.)
- ✅ Migrations y relaciones
- ✅ Factories para testing
- ✅ API REST con recursos
- ✅ Autenticación Sanctum
- ✅ Middleware y policies
- ✅ DTOs y services

### Frontend
- ✅ Login funcional
- ✅ Dashboard con estadísticas
- ✅ Gestión de reservaciones (CRUD)
- ✅ Layout responsive premium
- ✅ Mock data con fallback a API
- ✅ Todo en español
- ✅ State management con Zustand
- ✅ Routing protegido

## Próximos Pasos

1. Configurar CORS en backend
2. Implementar endpoints API faltantes
3. Conectar frontend con backend real
4. Agregar más módulos (Clientes, Reportes, Staff)
5. Testing end-to-end
6. Deploy

## Soporte

Para issues y preguntas, revisar el código o contactar al equipo de desarrollo.
