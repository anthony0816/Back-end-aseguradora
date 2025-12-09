# API Documentation - Risk Control System

## Base URL
```
http://localhost:8000/api
```

## Tabla de Contenidos
1. [Autenticación](#autenticación)
2. [Usuarios](#usuarios)
3. [Cuentas](#cuentas)
4. [Trades](#trades)
5. [Tipos de Reglas](#tipos-de-reglas)
6. [Reglas de Riesgo](#reglas-de-riesgo)
7. [Acciones de Riesgo](#acciones-de-riesgo)
8. [Incidentes](#incidentes)
9. [Notificaciones](#notificaciones)
10. [Evaluación de Riesgo](#evaluación-de-riesgo)
11. [Dashboard](#dashboard)
12. [Webhooks](#webhooks)

---

## Autenticación

### POST /register - Registrar Usuario

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "is_admin": false
}
```

**Response:** 201 Created
```json
{
  "message": "Usuario registrado exitosamente.",
  "user": {
    "id": 3,
    "name": "John Doe",
    "email": "john@example.com",
    "is_admin": false,
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  },
  "access_token": "3|abcdef123456...",
  "token_type": "Bearer"
}
```

### POST /login - Iniciar Sesión

**Request:**
```json
{
  "email": "admin@riskcontrol.com",
  "password": "password123"
}
```

**Response:** 200 OK
```json
{
  "message": "Login exitoso.",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@riskcontrol.com",
    "is_admin": true,
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  },
  "access_token": "2|pvRTNpfHZMnWgaccCVU4ZlIADna7rIQTkUWcYIEma2f68c84",
  "token_type": "Bearer"
}
```

**Error Response:** 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["Las credenciales proporcionadas son incorrectas."]
  }
}
```

### POST /logout - Cerrar Sesión

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** 200 OK
```json
{
  "message": "Logout exitoso."
}
```

### GET /me - Usuario Actual

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** 200 OK
```json
{
  "id": 1,
  "name": "Admin User",
  "email": "admin@riskcontrol.com",
  "is_admin": true,
  "created_at": "2025-12-08T23:14:48.000000Z",
  "updated_at": "2025-12-08T23:14:48.000000Z"
}
```

---

## Usuarios

### GET /users - Listar Usuarios

**Response:** 200 OK
```json
[
  {
    "id": 1,
    "name": "Admin User",
    "email": "admin@riskcontrol.com",
    "is_admin": true,
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  },
  {
    "id": 2,
    "name": "Trader User",
    "email": "trader@riskcontrol.com",
    "is_admin": false,
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  }
]
```

### POST /users - Crear Usuario

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "is_admin": false
}
```

**Response:** 201 Created
```json
{
  "message": "Usuario creado exitosamente.",
  "data": {
    "id": 3,
    "name": "John Doe",
    "email": "john@example.com",
    "is_admin": false,
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  }
}
```

### GET /users/{id} - Ver Usuario

**Response:** 200 OK
```json
{
  "id": 1,
  "name": "Admin User",
  "email": "admin@riskcontrol.com",
  "is_admin": true,
  "created_at": "2025-12-08T23:14:48.000000Z",
  "updated_at": "2025-12-08T23:14:48.000000Z",
  "accounts": [
    {
      "id": 1,
      "owner_id": 1,
      "login": 123456789,
      "trading_status": "enable",
      "status": "enable"
    }
  ],
  "notifications": [],
  "risk_rules": []
}
```

### PUT /users/{id} - Actualizar Usuario

**Request:**
```json
{
  "name": "John Doe Updated",
  "email": "john.updated@example.com"
}
```

**Response:** 200 OK
```json
{
  "message": "Usuario actualizado exitosamente.",
  "data": {
    "id": 3,
    "name": "John Doe Updated",
    "email": "john.updated@example.com",
    "is_admin": false,
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:30:00.000000Z"
  }
}
```

### DELETE /users/{id} - Eliminar Usuario

**Response:** 200 OK
```json
{
  "message": "Usuario eliminado exitosamente."
}
```

---

## Cuentas

### GET /accounts - Listar Cuentas

**Response:** 200 OK
```json
[
  {
    "id": 1,
    "owner_id": 1,
    "login": 123456789,
    "trading_status": "enable",
    "status": "enable",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z",
    "owner": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@riskcontrol.com"
    },
    "trades": []
  }
]
```

### POST /accounts - Crear Cuenta

**Request:**
```json
{
  "owner_id": 1,
  "login": 987654321,
  "trading_status": "enable",
  "status": "enable"
}
```

**Validaciones:**
- `owner_id`: requerido, debe existir en tabla users
- `login`: requerido, entero, único, mínimo 1
- `trading_status`: requerido, valores: "enable" o "disable"
- `status`: requerido, valores: "enable" o "disable"

**Response:** 201 Created
```json
{
  "message": "Cuenta creada exitosamente.",
  "data": {
    "id": 2,
    "owner_id": 1,
    "login": 987654321,
    "trading_status": "enable",
    "status": "enable",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  }
}
```

### GET /accounts/{id} - Ver Cuenta

**Response:** 200 OK
```json
{
  "id": 1,
  "owner_id": 1,
  "login": 123456789,
  "trading_status": "enable",
  "status": "enable",
  "created_at": "2025-12-08T23:14:48.000000Z",
  "updated_at": "2025-12-08T23:14:48.000000Z",
  "owner": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@riskcontrol.com",
    "is_admin": true
  },
  "trades": [
    {
      "id": 1,
      "account_id": 1,
      "type": "BUY",
      "volume": "1.5000",
      "status": "open"
    }
  ],
  "incidents": []
}
```

### PUT /accounts/{id} - Actualizar Cuenta

**Request:**
```json
{
  "trading_status": "disable",
  "status": "enable"
}
```

**Response:** 200 OK
```json
{
  "message": "Cuenta actualizada exitosamente.",
  "data": {
    "id": 1,
    "owner_id": 1,
    "login": 123456789,
    "trading_status": "disable",
    "status": "enable",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:30:00.000000Z"
  }
}
```

### DELETE /accounts/{id} - Eliminar Cuenta

**Response:** 204 No Content
```json
null
```

---

## Trades

### GET /trades - Listar Trades

**Response:** 200 OK
```json
[
  {
    "id": 1,
    "account_id": 1,
    "type": "BUY",
    "volume": "1.5000",
    "open_time": "2025-12-08T10:00:00.000000Z",
    "close_time": null,
    "open_price": "1.23450",
    "close_price": null,
    "status": "open",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z",
    "account": {
      "id": 1,
      "login": 123456789,
      "owner": {
        "id": 1,
        "name": "Admin User"
      }
    }
  }
]
```

### POST /trades - Crear Trade

**Request:**
```json
{
  "account_id": 1,
  "type": "BUY",
  "volume": 1.5,
  "open_time": "2025-12-08 10:00:00",
  "open_price": 1.2345,
  "status": "open"
}
```

**Validaciones:**
- `account_id`: requerido, debe existir en tabla accounts
- `type`: requerido, valores: "BUY" o "SELL"
- `volume`: requerido, numérico, mínimo 0
- `open_time`: requerido, formato fecha válido
- `close_time`: opcional, fecha, debe ser posterior a open_time
- `open_price`: requerido, numérico, mínimo 0
- `close_price`: opcional, numérico, mínimo 0
- `status`: requerido, valores: "open" o "closed"

**Response:** 201 Created
```json
{
  "message": "Trade creado exitosamente.",
  "data": {
    "id": 2,
    "account_id": 1,
    "type": "BUY",
    "volume": "1.5000",
    "open_time": "2025-12-08T10:00:00.000000Z",
    "close_time": null,
    "open_price": "1.23450",
    "close_price": null,
    "status": "open",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  }
}
```

### GET /trades/{id} - Ver Trade

**Response:** 200 OK
```json
{
  "id": 1,
  "account_id": 1,
  "type": "BUY",
  "volume": "1.5000",
  "open_time": "2025-12-08T10:00:00.000000Z",
  "close_time": "2025-12-08T10:05:00.000000Z",
  "open_price": "1.23450",
  "close_price": "1.23500",
  "status": "closed",
  "created_at": "2025-12-08T23:14:48.000000Z",
  "updated_at": "2025-12-08T23:20:00.000000Z",
  "account": {
    "id": 1,
    "login": 123456789,
    "trading_status": "enable",
    "status": "enable"
  },
  "incidents": [
    {
      "id": 1,
      "risk_rule_id": 1,
      "count": 1,
      "triggered_value": "Duration: 30s < 60s"
    }
  ]
}
```

### PUT /trades/{id} - Actualizar Trade (Cerrar)

**Request:**
```json
{
  "close_time": "2025-12-08 10:05:00",
  "close_price": 1.2350,
  "status": "closed"
}
```

**Response:** 200 OK
```json
{
  "message": "Trade actualizado exitosamente.",
  "data": {
    "id": 1,
    "account_id": 1,
    "type": "BUY",
    "volume": "1.5000",
    "open_time": "2025-12-08T10:00:00.000000Z",
    "close_time": "2025-12-08T10:05:00.000000Z",
    "open_price": "1.23450",
    "close_price": "1.23500",
    "status": "closed",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:20:00.000000Z"
  }
}
```

### DELETE /trades/{id} - Eliminar Trade

**Response:** 200 OK
```json
{
  "message": "Trade eliminado exitosamente."
}
```

---

## Tipos de Reglas

### GET /risk-rule-slugs - Listar Tipos de Reglas

**Response:** 200 OK
```json
[
  {
    "id": 1,
    "name": "Duration Check",
    "slug": "duration-check",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  },
  {
    "id": 2,
    "name": "Volume Consistency",
    "slug": "volume-consistency",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  },
  {
    "id": 3,
    "name": "Time Range Operation",
    "slug": "time-range-operation",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  }
]
```

### POST /risk-rule-slugs - Crear Tipo de Regla

**Request:**
```json
{
  "name": "Max Lot Size",
  "slug": "max-lot-size"
}
```

**Response:** 201 Created
```json
{
  "message": "Tipo de regla creado exitosamente.",
  "data": {
    "id": 6,
    "name": "Max Lot Size",
    "slug": "max-lot-size",
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z"
  }
}
```

---

## Reglas de Riesgo

### GET /risk-rules - Listar Reglas

**Response:** 200 OK
```json
[
  {
    "id": 1,
    "created_by_user_id": 1,
    "rule_type_id": 1,
    "name": "Minimum Trade Duration 60s",
    "description": "Trade must be open for at least 60 seconds",
    "severity": "Hard",
    "is_active": true,
    "parameter_id": 1,
    "created_at": "2025-12-08T23:14:48.000000Z",
    "updated_at": "2025-12-08T23:14:48.000000Z",
    "creator": {
      "id": 1,
      "name": "Admin User"
    },
    "rule_type": {
      "id": 1,
      "name": "Duration Check",
      "slug": "duration-check"
    },
    "actions": [
      {
        "id": 1,
        "name": "Notificar Email",
        "slug": "notify-email"
      }
    ]
  }
]
```

### POST /risk-rules - Crear Regla (Duration)

**Request:**
```json
{
  "created_by_user_id": 1,
  "rule_type_id": 1,
  "name": "Minimum Trade Duration 60s",
  "description": "Trade must be open for at least 60 seconds",
  "severity": "Hard",
  "is_active": true,
  "parameter_type": "duration",
  "parameter_data": {
    "duration": 60
  },
  "action_ids": [1, 2]
}
```

**Response:** 201 Created
```json
{
  "message": "Regla de riesgo creada exitosamente.",
  "data": {
    "id": 2,
    "name": "Minimum Trade Duration 60s",
    "severity": "Hard",
    "parameter": {
      "id": 2,
      "duration_parameter": {
        "parameter_id": 2,
        "duration": 60
      }
    },
    "actions": [
      {
        "id": 1,
        "name": "Notificar Email"
      }
    ]
  }
}
```

### POST /risk-rules - Crear Regla (Volume)

**Request:**
```json
{
  "created_by_user_id": 1,
  "rule_type_id": 2,
  "name": "Volume Consistency Check",
  "description": "Volume must be within 0.5x to 2x of average",
  "severity": "Soft",
  "is_active": true,
  "parameter_type": "volume",
  "parameter_data": {
    "min_factor": 0.5,
    "max_factor": 2.0,
    "lookback_trades": 10
  },
  "action_ids": [1]
}
```

### POST /risk-rules - Crear Regla (Time Range)

**Request:**
```json
{
  "created_by_user_id": 1,
  "rule_type_id": 3,
  "name": "Max Open Trades in Window",
  "description": "Maximum 5 open trades in 60 minutes",
  "severity": "Hard",
  "is_active": true,
  "parameter_type": "time_range",
  "parameter_data": {
    "time_window_minutes": 60,
    "min_open_trades": 1,
    "max_open_trades": 5
  },
  "action_ids": [2, 3]
}
```

---

## Dashboard

### GET /dashboard/stats - Estadísticas del Sistema

**Response:** 200 OK
```json
{
  "total_users": 2,
  "total_accounts": 2,
  "active_accounts": 1,
  "total_trades": 2,
  "open_trades": 1,
  "closed_trades": 1,
  "total_rules": 1,
  "active_rules": 1,
  "total_incidents": 1,
  "executed_incidents": 1
}
```

### GET /dashboard/recent-incidents - Incidentes Recientes

**Query Parameters:**
- `limit`: número de incidentes (default: 10)

**Response:** 200 OK
```json
[
  {
    "id": 1,
    "account_id": 1,
    "risk_rule_id": 1,
    "trade_id": 1,
    "count": 1,
    "triggered_value": "Duration: 30s < 60s",
    "is_executed": true,
    "created_at": "2025-12-08T23:14:48.000000Z",
    "account": {
      "id": 1,
      "login": 123456789
    },
    "risk_rule": {
      "id": 1,
      "name": "Minimum Trade Duration 60s"
    }
  }
]
```

### GET /dashboard/account-risk-profile/{accountId} - Perfil de Riesgo

**Response:** 200 OK
```json
{
  "account": {
    "id": 1,
    "login": 123456789,
    "trading_status": "disable",
    "status": "enable",
    "owner": {
      "id": 1,
      "name": "Admin User"
    }
  },
  "total_trades": 5,
  "open_trades": 1,
  "total_incidents": 3,
  "recent_incidents": [
    {
      "id": 1,
      "risk_rule": {
        "id": 1,
        "name": "Minimum Trade Duration 60s"
      },
      "count": 1,
      "created_at": "2025-12-08T23:14:48.000000Z"
    }
  ],
  "violations_by_rule": [
    {
      "risk_rule_id": 1,
      "total": 3,
      "risk_rule": {
        "id": 1,
        "name": "Minimum Trade Duration 60s"
      }
    }
  ]
}
```

---

## Webhooks

### POST /webhook/trade - Recibir Trade desde Sistema Externo

**Request:**
```json
{
  "account_login": 123456789,
  "type": "BUY",
  "volume": 1.5,
  "open_time": "2025-12-08 10:00:00",
  "open_price": 1.2345,
  "status": "open",
  "external_id": "MT4_12345"
}
```

**Response:** 201 Created
```json
{
  "success": true,
  "message": "Trade registrado exitosamente.",
  "trade_id": 1,
  "violations_detected": 0,
  "violations": []
}
```

**Response (Con violaciones):** 201 Created
```json
{
  "success": true,
  "message": "Trade registrado exitosamente.",
  "trade_id": 2,
  "violations_detected": 1,
  "violations": [
    {
      "rule": "Minimum Trade Duration 60s",
      "severity": "Hard",
      "incident_id": 1
    }
  ]
}
```

**Error Response (Cuenta no encontrada):** 404 Not Found
```json
{
  "success": false,
  "message": "Cuenta no encontrada."
}
```

**Error Response (Cuenta deshabilitada):** 403 Forbidden
```json
{
  "success": false,
  "message": "Cuenta deshabilitada.",
  "account_status": "disable",
  "trading_status": "disable"
}
```

### PUT /webhook/trade/{externalId} - Actualizar Trade

**Request:**
```json
{
  "close_time": "2025-12-08 10:05:00",
  "close_price": 1.2350,
  "status": "closed"
}
```

**Response:** 200 OK
```json
{
  "success": true,
  "message": "Trade actualizado y evaluado.",
  "violations_detected": 1,
  "violations": [
    {
      "rule": "Minimum Trade Duration 60s",
      "severity": "Hard",
      "incident_id": 1
    }
  ]
}
```

### GET /webhook/health - Health Check

**Response:** 200 OK
```json
{
  "status": "ok",
  "service": "Risk Control Webhook",
  "timestamp": "2025-12-08T23:30:00+00:00"
}
```

---

## Evaluación de Riesgo

### POST /risk-evaluation/trade/{tradeId} - Evaluar Trade

**Response (Sin violaciones):** 200 OK
```json
{
  "message": "Trade evaluado sin violaciones.",
  "violations": []
}
```

**Response (Con violaciones):** 200 OK
```json
{
  "message": "Se detectaron violaciones de reglas.",
  "violations": [
    {
      "rule": "Minimum Trade Duration 60s",
      "severity": "Hard",
      "incident_id": 1
    }
  ]
}
```

### POST /risk-evaluation/account/{accountId} - Evaluar Cuenta

**Response:** 200 OK
```json
{
  "message": "Evaluación de cuenta completada.",
  "total_trades": 5,
  "violations": {
    "1": [
      {
        "rule": "Minimum Trade Duration 60s",
        "severity": "Hard",
        "incident_id": 1
      }
    ],
    "3": [
      {
        "rule": "Volume Consistency Check",
        "severity": "Soft",
        "incident_id": 2
      }
    ]
  }
}
```

---

## Códigos de Estado HTTP

- `200 OK` - Solicitud exitosa
- `201 Created` - Recurso creado exitosamente
- `204 No Content` - Solicitud exitosa sin contenido de respuesta
- `400 Bad Request` - Solicitud mal formada
- `401 Unauthorized` - No autenticado
- `403 Forbidden` - No autorizado
- `404 Not Found` - Recurso no encontrado
- `422 Unprocessable Entity` - Error de validación
- `500 Internal Server Error` - Error del servidor

---

## Credenciales por Defecto

- **Admin:** admin@riskcontrol.com / password123
- **Trader:** trader@riskcontrol.com / password123

---

## Notas para Desarrolladores Frontend

1. **Autenticación:** 
   - **Rutas Públicas (sin token):**
     - POST /register
     - POST /login
     - POST /webhook/trade
     - PUT /webhook/trade/{id}
     - GET /webhook/health
   - **Rutas Protegidas (requieren token):** Todos los demás endpoints requieren el header `Authorization: Bearer {token}`

2. **Expiración de Tokens:** 
   - Por defecto: Los tokens **NO expiran** (duran indefinidamente)
   - Configurable en `.env` con `SANCTUM_EXPIRATION` (en minutos)
   - Ejemplos: 60 = 1 hora, 1440 = 24 horas, 10080 = 7 días
   - Los tokens se invalidan al hacer logout

3. **Formato de Fechas:** Usar formato ISO 8601 o `YYYY-MM-DD HH:MM:SS`

3. **Validación:** Los errores de validación retornan código 422 con detalles en el campo `errors`

4. **Paginación:** Actualmente no implementada, todos los endpoints retornan todos los registros

5. **CORS:** Configurado para aceptar peticiones desde cualquier origen en desarrollo

6. **Tipos de Parámetros:**
   - `duration`: Requiere campo `duration` (segundos)
   - `volume`: Requiere `min_factor`, `max_factor`, `lookback_trades`
   - `time_range`: Requiere `time_window_minutes`, `min_open_trades`, `max_open_trades`

7. **Severidad de Reglas:**
   - `Hard`: Ejecuta acciones inmediatamente
   - `Soft`: Ejecuta acciones después de 3 violaciones

8. **Estados de Cuenta:**
   - `status`: Estado general de la cuenta (enable/disable)
   - `trading_status`: Estado específico del trading (enable/disable)

9. **Estados de Trade:**
   - `open`: Trade abierto
   - `closed`: Trade cerrado

10. **Webhooks:** Diseñados para recibir trades desde plataformas externas (MT4/MT5) sin autenticación
