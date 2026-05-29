# LIDO Beach Club - Frontend

Frontend del sistema de gestión para beach clubs construido con React, TypeScript y Vite.

## Tecnologías

- **React 18** - Biblioteca UI
- **TypeScript** - Tipado estático
- **Vite** - Build tool y dev server
- **Tailwind CSS** - Framework CSS utility-first
- **React Router** - Enrutamiento
- **Zustand** - State management
- **Axios** - Cliente HTTP
- **Heroicons** - Iconos

## Estructura del Proyecto

```
src/
├── api/           # Cliente Axios y configuración API
├── components/    # Componentes reutilizables
├── hooks/         # Custom React hooks
├── layouts/       # Componentes de layout
├── pages/         # Páginas de la aplicación
├── routes/        # Configuración de rutas
├── services/      # Servicios de API
├── stores/        # Zustand stores
├── types/         # TypeScript types
└── utils/         # Funciones utilitarias
```

## Instalación

```bash
npm install
```

## Desarrollo

```bash
npm run dev
```

El servidor de desarrollo estará disponible en `http://localhost:5173`

## Build para Producción

```bash
npm run build
```

Los archivos generados estarán en el directorio `dist/`

## Preview de Producción

```bash
npm run preview
```

## Características Principales

### Autenticación
- Login con email y contraseña
- Gestión de sesión con JWT
- Rutas protegidas
- Persistencia de sesión

### Dashboard
- Vista general de estadísticas
- Reservaciones del día
- Métricas de ocupación y revenue
- Acciones rápidas

### Reservaciones
- Lista completa de reservaciones
- Creación de nuevas reservaciones
- Filtros por estado
- Búsqueda por cliente
- Actualización de estados
- Datos mock con fallback a API

### Datos de Demo

**Usuario de prueba:**
- Email: `admin@lido.com`
- Contraseña: `admin123`

## Integración con Backend

El frontend está configurado para conectarse a una API REST en `/api/v1`.

Los servicios incluyen fallbacks a datos mock para desarrollo sin backend:
- `authService` - Autenticación
- `reservationService` - Gestión de reservaciones
- `dashboardService` - Estadísticas

## Configuración de API

Editar `src/api/client.ts` para cambiar la URL base de la API:

```typescript
const apiClient = axios.create({
  baseURL: '/api/v1', // Cambiar según necesidad
});
```

## Interceptors de Axios

- **Request**: Agrega automáticamente el token JWT a todas las peticiones
- **Response**: Maneja errores 401 redirigiendo al login

## State Management

Zustand se usa para:
- Estado de autenticación (`authStore`)
- Usuario actual
- Beach club seleccionado

## Estilos y Tema

Tailwind CSS configurado con:
- Colores personalizados (primary, etc.)
- Clases utilitarias custom
- Responsive design
- Componentes reutilizables

## Próximos Módulos

- Clientes
- Reportes
- Configuración
- Amenidades
- Staff

## Licencia

Propietario - LIDO Beach Club
