# Risk Control System - Backend API

Sistema completo de gestión de riesgo para cuentas de trading, desarrollado con Laravel 10.

## 🚀 Características

- **Gestión de Usuarios**: Administradores y traders con autenticación JWT
- **Cuentas de Trading**: Control de cuentas con estados habilitados/deshabilitados
- **Trades**: Registro completo de operaciones (BUY/SELL)
- **Reglas de Riesgo**: Sistema flexible de reglas configurables
  - Duration Check: Validación de duración mínima de trades
  - Volume Consistency: Consistencia de volumen basada en histórico
  - Time Range Operations: Control de operaciones en ventanas de tiempo
- **Acciones Automáticas**: Notificaciones, deshabilitación de cuentas, etc.
- **Incidentes**: Registro de violaciones con contadores para reglas Soft/Hard
- **Dashboard**: Estadísticas y perfiles de riesgo por cuenta

## 📋 Requisitos

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js y NPM (opcional)

## ⚡ Instalación Rápida

### Windows
```bash
install.bat
```

### Linux/Mac
```bash
chmod +x install.sh
./install.sh
```

### Manual
```bash
composer install
cp .env.example .env
# Configurar .env con credenciales de MySQL
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## 📚 Documentación

- **[SETUP.md](SETUP.md)** - Guía detallada de instalación
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Documentación completa de endpoints
- **[postman_collection.json](postman_collection.json)** - Colección de Postman para pruebas

## 🔑 Credenciales por Defecto

- **Admin**: `admin@riskcontrol.com` / `password123`
- **Trader**: `trader@riskcontrol.com` / `password123`

## 🛠️ Endpoints Principales

### Autenticación
- `POST /api/register` - Registro de usuario
- `POST /api/login` - Login
- `POST /api/logout` - Logout
- `GET /api/me` - Usuario actual

### Recursos
- `/api/users` - Gestión de usuarios
- `/api/accounts` - Cuentas de trading
- `/api/trades` - Operaciones
- `/api/risk-rules` - Reglas de riesgo
- `/api/risk-actions` - Acciones disponibles
- `/api/incidents` - Incidentes registrados
- `/api/notifications` - Notificaciones

### Evaluación de Riesgo
- `POST /api/risk-evaluation/trade/{id}` - Evaluar un trade
- `POST /api/risk-evaluation/account/{id}` - Evaluar cuenta completa

### Dashboard
- `GET /api/dashboard/stats` - Estadísticas generales
- `GET /api/dashboard/recent-incidents` - Incidentes recientes
- `GET /api/dashboard/account-risk-profile/{id}` - Perfil de riesgo

## 🗄️ Modelo de Datos

El sistema implementa un modelo completo con:
- Usuarios y cuentas
- Trades con estados open/closed
- Reglas de riesgo con tipos configurables
- Parámetros polimórficos (duration, volume, time_range)
- Sistema de acciones M:M con reglas
- Registro de incidentes con contadores

## 🔧 Comandos Útiles

```bash
# Ver todas las rutas
php artisan route:list

# Limpiar y reiniciar BD
php artisan migrate:fresh --seed

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## 📦 Estructura del Proyecto

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controladores de API
│   │   └── Middleware/      # Middleware personalizado
│   ├── Models/              # Modelos Eloquent
│   └── Services/            # Lógica de negocio
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/             # Datos iniciales
├── routes/
│   └── api.php              # Rutas de API
└── config/                  # Configuración
```

## 🧪 Testing

```bash
php artisan test
```

## 📝 Licencia

Este proyecto es privado y confidencial.
