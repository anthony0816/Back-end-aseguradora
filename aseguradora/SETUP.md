# Setup Instructions - Risk Control Backend

## Requisitos Previos
- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js y NPM (opcional, para assets)

## Instalación

### 1. Instalar Dependencias
```bash
cd backend
composer install
```

### 2. Configurar Base de Datos

Crear base de datos MySQL:
```sql
CREATE DATABASE risk_control;
```

### 3. Configurar Variables de Entorno
```bash
cp .env.example .env
```

Editar `.env` con tus credenciales de MySQL:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=risk_control
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### 4. Generar Application Key
```bash
php artisan key:generate
```

### 5. Ejecutar Migraciones
```bash
php artisan migrate
```

### 6. Ejecutar Seeders (Datos Iniciales)
```bash
php artisan db:seed
```

Esto creará:
- 2 usuarios (admin y trader)
- Tipos de reglas de riesgo
- Acciones de riesgo disponibles

### 7. Iniciar Servidor de Desarrollo
```bash
php artisan serve
```

El servidor estará disponible en: `http://localhost:8000`

## Verificar Instalación

### Probar API
```bash
# Listar usuarios
curl http://localhost:8000/api/users

# Listar tipos de reglas
curl http://localhost:8000/api/risk-rule-slugs

# Listar acciones de riesgo
curl http://localhost:8000/api/risk-actions
```

## Comandos Útiles

### Limpiar y Reiniciar Base de Datos
```bash
php artisan migrate:fresh --seed
```

### Ver Rutas Disponibles
```bash
php artisan route:list
```

### Limpiar Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Estructura de la Base de Datos

El sistema incluye las siguientes tablas:
- `users` - Usuarios del sistema
- `accounts` - Cuentas de trading
- `trades` - Operaciones de trading
- `notifications` - Notificaciones a usuarios
- `risk_rule_slugs` - Catálogo de tipos de reglas
- `risk_rules` - Reglas de riesgo configuradas
- `risk_actions` - Acciones disponibles
- `rule_actions` - Relación M:M entre reglas y acciones
- `parameters` - Tabla padre de parámetros
- `duration_parameters` - Parámetros de duración
- `parameter_volume_trades` - Parámetros de volumen
- `parameter_time_range_operations` - Parámetros de rango temporal
- `incidents` - Registro de violaciones

## Próximos Pasos

1. Revisar la documentación de la API en `API_DOCUMENTATION.md`
2. Crear usuarios adicionales según necesidad
3. Configurar reglas de riesgo personalizadas
4. Integrar con sistema de trading existente
