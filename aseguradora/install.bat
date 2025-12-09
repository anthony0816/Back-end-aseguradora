@echo off
echo ========================================
echo Risk Control Backend - Instalacion
echo ========================================
echo.

echo [1/7] Instalando dependencias de Composer...
call composer install
if %errorlevel% neq 0 (
    echo Error: Fallo la instalacion de Composer
    pause
    exit /b 1
)

echo.
echo [2/7] Copiando archivo de configuracion...
if not exist .env (
    copy .env.example .env
    echo Archivo .env creado. Por favor, configura tus credenciales de base de datos.
) else (
    echo Archivo .env ya existe.
)

echo.
echo [3/7] Generando application key...
call php artisan key:generate

echo.
echo [4/7] Ejecutando migraciones...
call php artisan migrate
if %errorlevel% neq 0 (
    echo.
    echo ADVERTENCIA: Las migraciones fallaron.
    echo Asegurate de que:
    echo - MySQL este corriendo
    echo - La base de datos exista
    echo - Las credenciales en .env sean correctas
    echo.
    pause
    exit /b 1
)

echo.
echo [5/7] Ejecutando seeders...
call php artisan db:seed

echo.
echo [6/7] Limpiando cache...
call php artisan cache:clear
call php artisan config:clear
call php artisan route:clear

echo.
echo [7/7] Instalacion completada!
echo.
echo ========================================
echo Para iniciar el servidor, ejecuta:
echo php artisan serve
echo ========================================
echo.
echo Credenciales por defecto:
echo Admin: admin@riskcontrol.com / password123
echo Trader: trader@riskcontrol.com / password123
echo.
pause
