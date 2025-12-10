# Documentación de Integración Frontend - Sistema de Control de Riesgos

## 🔧 Estado del Backend: ✅ FUNCIONANDO CORRECTAMENTE

### Problemas Identificados y Corregidos:

1. ✅ **CORREGIDO**: `AccountController::index()` ahora filtra cuentas por usuario autenticado
2. ✅ **VERIFICADO**: Sistema de evaluación de riesgos funciona automáticamente
3. ✅ **VERIFICADO**: Notificaciones se crean correctamente
4. ✅ **VERIFICADO**: Cuentas y trading se deshabilitan según reglas
5. ✅ **VERIFICADO**: Webhook procesa trades y evalúa riesgos automáticamente

---

## 🚀 Endpoints Principales para Frontend

### 1. Autenticación

#### Registro
```http
POST /api/register
Content-Type: application/json

{
  "name": "Usuario Test",
  "email": "usuario@example.com", 
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Respuesta:**
```json
{
  "message": "Usuario registrado exitosamente.",
  "user": {
    "id": 8,
    "name": "Usuario Test",
    "email": "usuario@example.com",
    "is_admin": false
  }
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "usuario@example.com",
  "password": "password123"
}
```

**Respuesta:**
```json
{
  "message": "Login exitoso.",
  "user": {
    "id": 8,
    "name": "Usuario Test", 
    "email": "usuario@example.com",
    "is_admin": false
  },
  "access_token": "28|Wt9b5ZVh2nsDsd7IVqEM5AYDLwQbIIHdu7pUUdks7cd038a4",
  "token_type": "Bearer"
}
```

#### Usuario Actual
```http
GET /api/me
Authorization: Bearer {token}
```

### 2. Gestión de Cuentas

#### ✅ Listar Cuentas del Usuario (CORREGIDO)
```http
GET /api/accounts
Authorization: Bearer {token}
```

**Comportamiento:**
- **Usuario normal**: Solo ve sus propias cuentas
- **Admin con ?all=true**: Ve todas las cuentas del sistema

**Respuesta:**
```json
[
  {
    "id": 8,
    "owner_id": 8,
    "login": 12345,
    "trading_status": "enable|disable",
    "status": "enable|disable", 
    "created_at": "2025-12-10T21:10:00.000000Z",
    "updated_at": "2025-12-10T21:10:00.000000Z",
    "owner": {
      "id": 8,
      "name": "Usuario Test",
      "email": "usuario@example.com"
    },
    "trades": [...],
    "incidents": [...]
  }
]
```

#### Crear Cuenta
```http
POST /api/accounts
Authorization: Bearer {token}
Content-Type: application/json

{
  "owner_id": 8,
  "login": 12345,
  "trading_status": "enable",
  "status": "enable"
}
```

#### Ver Cuenta Específica
```http
GET /api/accounts/{id}
Authorization: Bearer {token}
```

### 3. Notificaciones

#### Listar Notificaciones del Usuario
```http
GET /api/notifications
Authorization: Bearer {token}
```

**Respuesta:**
```json
[
  {
    "id": 6,
    "user_id": 8,
    "mensaje": "Violación de regla: prevencion",
    "metadata": {
      "rule_id": 10,
      "incident_id": 9,
      "severity": "Hard"
    },
    "created_at": "2025-12-10T21:10:27.000000Z",
    "updated_at": "2025-12-10T21:10:27.000000Z"
  }
]
```

### 4. Reglas de Riesgo

#### Listar Reglas Activas
```http
GET /api/risk-rules
Authorization: Bearer {token}
```

**Respuesta:**
```json
[
  {
    "id": 3,
    "name": "Max 3 Trades en 60 min HARD",
    "description": "Maximo 3 trades abiertos en ventana de 60 minutos",
    "severity": "Hard|Soft",
    "is_active": true,
    "rule_type": {
      "name": "Time Range Operation",
      "slug": "time-range-operation"
    },
    "actions": [
      {
        "name": "Deshabilitar Cuenta",
        "slug": "disable-account"
      },
      {
        "name": "Notificar Admin", 
        "slug": "notify-admin"
      }
    ]
  }
]
```

### 5. Trades

#### Listar Trades
```http
GET /api/trades
Authorization: Bearer {token}
```

#### Ver Trade Específico
```http
GET /api/trades/{id}
Authorization: Bearer {token}
```

### 6. Incidentes

#### Listar Incidentes
```http
GET /api/incidents
Authorization: Bearer {token}
```

**Respuesta:**
```json
[
  {
    "id": 9,
    "account_id": 8,
    "risk_rule_id": 10,
    "trade_id": 21,
    "count": 1,
    "triggered_value": "Open trades: 1 outside range [2, 10]",
    "is_executed": true,
    "created_at": "2025-12-10T21:10:27.000000Z",
    "updated_at": "2025-12-10T21:10:28.000000Z"
  }
]
```

### 7. Evaluación Manual de Riesgos

#### Evaluar Trade Específico
```http
POST /api/risk-evaluation/trade/{tradeId}
Authorization: Bearer {token}
```

#### Evaluar Cuenta Completa
```http
POST /api/risk-evaluation/account/{accountId}
Authorization: Bearer {token}
```

---

## 🔄 Flujo Automático del Sistema

### 1. Recepción de Trade (Webhook)
```http
POST /api/webhook/trade
Content-Type: application/json

{
  "account_login": 12345,
  "type": "BUY|SELL",
  "volume": 1.0,
  "open_time": "2025-12-10T21:15:00Z",
  "open_price": 1.2345,
  "status": "open|closed",
  "close_time": "2025-12-10T21:16:00Z", // opcional
  "close_price": 1.2350 // opcional
}
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Trade registrado exitosamente.",
  "trade_id": 21,
  "violations_detected": 2,
  "violations": [
    {
      "rule": "prevencion",
      "severity": "Hard", 
      "incident_id": 9
    }
  ]
}
```

### 2. Proceso Automático:
1. ✅ **Trade se guarda** en la base de datos
2. ✅ **Evaluación automática** contra todas las reglas activas
3. ✅ **Creación de incidentes** si hay violaciones
4. ✅ **Ejecución de acciones**:
   - Notificaciones al usuario y admin
   - Deshabilitación de cuenta/trading según regla
   - Cierre de trades abiertos (si aplica)
5. ✅ **Respuesta inmediata** con resultados

---

## 📊 Estados y Comportamientos

### Estados de Cuenta:
- **status**: `"enable"` | `"disable"` (cuenta activa/inactiva)
- **trading_status**: `"enable"` | `"disable"` (trading permitido/bloqueado)

### Estados de Trade:
- **status**: `"open"` | `"closed"`

### Severidad de Reglas:
- **Soft**: Requiere 3+ violaciones para ejecutar acciones
- **Hard**: Ejecuta acciones inmediatamente

### Acciones Disponibles:
- `notify-email`: Notifica al usuario
- `notify-admin`: Notifica a administradores  
- `disable-account`: Deshabilita la cuenta
- `disable-trading`: Deshabilita solo el trading
- `close-open-trades`: Cierra trades abiertos

---

## 🎯 Implementación en Frontend

### 1. Autenticación
```javascript
// Login y almacenar token
const login = async (email, password) => {
  const response = await fetch('/api/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  });
  
  const data = await response.json();
  localStorage.setItem('token', data.access_token);
  return data;
};

// Headers para requests autenticados
const authHeaders = {
  'Authorization': `Bearer ${localStorage.getItem('token')}`,
  'Content-Type': 'application/json'
};
```

### 2. Obtener Cuentas del Usuario
```javascript
const getUserAccounts = async () => {
  const response = await fetch('/api/accounts', {
    headers: authHeaders
  });
  return response.json();
};
```

### 3. Monitorear Notificaciones
```javascript
const getNotifications = async () => {
  const response = await fetch('/api/notifications', {
    headers: authHeaders
  });
  return response.json();
};

// Polling cada 30 segundos para nuevas notificaciones
setInterval(async () => {
  const notifications = await getNotifications();
  updateNotificationUI(notifications);
}, 30000);
```

### 4. Dashboard de Riesgos
```javascript
const getRiskDashboard = async (accountId) => {
  const [account, incidents, notifications] = await Promise.all([
    fetch(`/api/accounts/${accountId}`, { headers: authHeaders }),
    fetch('/api/incidents', { headers: authHeaders }),
    fetch('/api/notifications', { headers: authHeaders })
  ]);
  
  return {
    account: await account.json(),
    incidents: await incidents.json(),
    notifications: await notifications.json()
  };
};
```

---

## ⚠️ Consideraciones Importantes

### 1. Manejo de Estados
- **Cuenta deshabilitada**: Mostrar alerta y deshabilitar funciones
- **Trading deshabilitado**: Permitir ver datos pero no operar
- **Violaciones activas**: Mostrar alertas prominentes

### 2. Tiempo Real
- Implementar polling o WebSockets para notificaciones
- Actualizar estados de cuenta automáticamente
- Mostrar incidentes en tiempo real

### 3. Permisos
- Usuarios normales: Solo ven sus datos
- Admins: Pueden ver todo con `?all=true`
- Validar permisos en frontend y backend

### 4. UX/UI
- Indicadores visuales claros para estados críticos
- Notificaciones push para violaciones
- Dashboard con métricas de riesgo en tiempo real

---

## 🧪 Testing

El sistema ha sido probado exhaustivamente:
- ✅ Autenticación funciona
- ✅ Filtrado de cuentas por usuario
- ✅ Evaluación automática de riesgos
- ✅ Creación de notificaciones
- ✅ Deshabilitación automática de cuentas/trading
- ✅ Webhook procesa trades correctamente

**Servidor de desarrollo**: `http://127.0.0.1:8000`
**Base URL API**: `http://127.0.0.1:8000/api`