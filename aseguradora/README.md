# 🛡️ Sistema de Control de Riesgos - Backend

API REST desarrollada con **Laravel 10** para el sistema de control de riesgos de trading. Proporciona evaluación automática de riesgos, gestión de cuentas, notificaciones en tiempo real y un sistema completo de auditoría.

## 🚀 Características Principales

### 🔐 Autenticación y Autorización
- **Laravel Sanctum** para autenticación API
- **Roles de usuario**: Admin y Usuario normal
- **Filtrado de datos** por propietario
- **Tokens de acceso** con expiración configurable

### 📊 Gestión de Trading
- **Cuentas de trading** con estados (activa/inactiva, trading habilitado/deshabilitado)
- **Trades** con seguimiento completo (apertura, cierre, volumen, precios)
- **Webhook** para recepción automática de trades
- **Evaluación de riesgos** en tiempo real

### ⚠️ Sistema de Control de Riesgos
- **Reglas personalizables** por usuario
- **Tipos de reglas**: Duración, Volumen, Operaciones por tiempo
- **Severidad**: Hard (acción inmediata) y Soft (después de 3 violaciones)
- **Acciones automáticas**: Notificar, deshabilitar cuenta/trading, cerrar trades

### 🔔 Sistema de Notificaciones
- **Notificaciones automáticas** para violaciones
- **Notificaciones de acciones** (cuenta deshabilitada, trading bloqueado)
- **Notificaciones para admins** con información completa
- **Metadata enriquecida** para trazabilidad

### 📈 Monitoreo y Auditoría
- **Incidentes** con seguimiento de ejecución
- **Logs de auditoría** para todas las acciones
- **Dashboard** con estadísticas en tiempo real
- **Filtrado por usuario** y acceso admin completo

## 🛠️ Instalación y Configuración

### Prerrequisitos
- **PHP 8.1+**
- **Composer**
- **MySQL 8.0+** o **MariaDB 10.3+**
- **Node.js 16+** (para assets)

### 1. Clonar el Repositorio
```bash
git clone <repository-url>
cd "Back end/aseguradora"
```

### 2. Instalar Dependencias
```bash
composer install
```

### 3. Configurar Variables de Entorno
```bash
cp .env.example .env
```

**Configurar las siguientes variables obligatorias:**
```env
# Base de datos
DB_DATABASE=risk_control_system
DB_USERNAME=tu_usuario_db
DB_PASSWORD=tu_password_db

# Aplicación
APP_KEY=                    # Se genera automáticamente
APP_URL=http://127.0.0.1:8000

# Email (para notificaciones)
MAIL_FROM_ADDRESS=noreply@aseguradora.com
```

### 4. Generar Clave de Aplicación
```bash
php artisan key:generate
```

### 5. Configurar Base de Datos
```bash
# Crear la base de datos
mysql -u root -p -e "CREATE DATABASE risk_control_system;"

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (datos iniciales)
php artisan db:seed
```

### 6. Configurar Sanctum
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 7. Iniciar Servidor de Desarrollo
```bash
php artisan serve
```

El servidor estará disponible en: `http://127.0.0.1:8000`

## 📋 Configuración Detallada

### Base de Datos
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=risk_control_system
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### Email (Notificaciones)
**Para desarrollo (Mailpit):**
```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

**Para producción (Gmail):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_app_password
MAIL_ENCRYPTION=tls
```

### CORS (Para Frontend)
```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
```

## 🔗 Endpoints Principales

### Autenticación
- `POST /api/register` - Registro de usuarios
- `POST /api/login` - Iniciar sesión
- `GET /api/me` - Usuario actual
- `POST /api/logout` - Cerrar sesión

### Cuentas
- `GET /api/accounts` - Listar cuentas (filtradas por usuario)
- `GET /api/accounts?all=true` - Todas las cuentas (solo admin)
- `POST /api/accounts` - Crear cuenta
- `PUT /api/accounts/{id}` - Actualizar cuenta
- `DELETE /api/accounts/{id}` - Eliminar cuenta

### Trades
- `GET /api/trades` - Listar trades (filtrados por usuario)
- `GET /api/trades?all=true` - Todos los trades (solo admin)
- `POST /api/trades` - Crear trade
- `PUT /api/trades/{id}` - Actualizar trade
- `DELETE /api/trades/{id}` - Eliminar trade

### Webhook
- `POST /api/webhook/trade` - Recibir trade y evaluar riesgos

### Notificaciones
- `GET /api/notifications` - Listar notificaciones (filtradas por usuario)
- `GET /api/notifications?all=true` - Todas las notificaciones (solo admin)
- `DELETE /api/notifications/{id}` - Eliminar notificación

### Incidentes
- `GET /api/incidents` - Listar incidentes (filtrados por usuario)
- `GET /api/incidents?all=true` - Todos los incidentes (solo admin)

### Reglas de Riesgo
- `GET /api/risk-rules` - Listar reglas (filtradas por usuario)
- `GET /api/risk-rules?all=true` - Todas las reglas (solo admin)
- `POST /api/risk-rules` - Crear regla
- `PUT /api/risk-rules/{id}` - Actualizar regla
- `DELETE /api/risk-rules/{id}` - Eliminar regla

### Evaluación de Riesgos
- `POST /api/risk-evaluation/trade/{id}` - Evaluar trade específico
- `POST /api/risk-evaluation/account/{id}` - Evaluar cuenta completa

## 🔧 Comandos Útiles

### Desarrollo
```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Ver rutas
php artisan route:list

# Ejecutar tests
php artisan test

# Generar documentación API
php artisan l5-swagger:generate
```

### Base de Datos
```bash
# Refrescar base de datos
php artisan migrate:fresh --seed

# Crear migración
php artisan make:migration create_table_name

# Crear seeder
php artisan make:seeder TableSeeder
```

### Mantenimiento
```bash
# Modo mantenimiento
php artisan down
php artisan up

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🏗️ Arquitectura del Sistema

### Modelos Principales
- **User**: Usuarios del sistema (admin/normal)
- **Account**: Cuentas de trading
- **Trade**: Operaciones de trading
- **RiskRule**: Reglas de control de riesgo
- **Incident**: Incidentes por violaciones
- **Notification**: Notificaciones del sistema

### Servicios
- **RiskEvaluationService**: Evaluación de riesgos
- **NotificationService**: Gestión de notificaciones
- **WebhookService**: Procesamiento de webhooks

### Controladores
- **AuthController**: Autenticación
- **AccountController**: Gestión de cuentas
- **TradeController**: Gestión de trades
- **RiskRuleController**: Reglas de riesgo
- **NotificationController**: Notificaciones
- **WebhookController**: Recepción de trades

## 🔒 Seguridad

### Autenticación
- **Laravel Sanctum** para tokens API
- **Middleware de autenticación** en todas las rutas protegidas
- **Filtrado de datos** por propietario

### Autorización
- **Roles de usuario**: Admin puede ver todo con `?all=true`
- **Filtrado automático**: Usuarios ven solo sus datos
- **Validación de permisos** en cada endpoint

### Validación
- **Form Requests** para validación de datos
- **Sanitización** de inputs
- **Validación de tipos** y rangos

## 📊 Monitoreo y Logs

### Logs del Sistema
```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Logs por fecha
ls storage/logs/
```

### Métricas
- **Incidentes por usuario**
- **Violaciones por regla**
- **Acciones ejecutadas**
- **Notificaciones enviadas**

## 🚀 Despliegue en Producción

### 1. Configurar Servidor
```bash
# Instalar dependencias
composer install --optimize-autoloader --no-dev

# Optimizar aplicación
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Variables de Entorno
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Base de datos de producción
DB_HOST=tu-servidor-db
DB_DATABASE=risk_control_prod
DB_USERNAME=usuario_prod
DB_PASSWORD=password_seguro

# Email de producción
MAIL_MAILER=smtp
MAIL_HOST=smtp.tu-proveedor.com
```

### 3. Permisos
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📚 Documentación Adicional

- **API_DOCUMENTATION.md**: Documentación completa de la API
- **API_INTEGRATION_FRONTEND.md**: Guía de integración con frontend
- **PROYECTO_COMPLETADO.md**: Resumen del proyecto
- **PRUEBAS_API_RESULTADOS.md**: Resultados de pruebas

## 🤝 Contribución

1. Fork el proyecto
2. Crear rama feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

## 🆘 Soporte

Para soporte técnico o preguntas:
- **Email**: soporte@aseguradora.com
- **Documentación**: Ver archivos MD en el repositorio
- **Issues**: Crear issue en el repositorio

---

**Desarrollado con ❤️ usando Laravel 10**