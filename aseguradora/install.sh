#!/bin/bash

echo "========================================"
echo "Risk Control Backend - Instalación"
echo "========================================"
echo ""

echo "[1/7] Instalando dependencias de Composer..."
composer install
if [ $? -ne 0 ]; then
    echo "Error: Falló la instalación de Composer"
    exit 1
fi

echo ""
echo "[2/7] Copiando archivo de configuración..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Archivo .env creado. Por favor, configura tus credenciales de base de datos."
else
    echo "Archivo .env ya existe."
fi

echo ""
echo "[3/7] Generando application key..."
php artisan key:generate

echo ""
echo "[4/7] Ejecutando migraciones..."
php artisan migrate
if [ $? -ne 0 ]; then
    echo ""
    echo "ADVERTENCIA: Las migraciones fallaron."
    echo "Asegúrate de que:"
    echo "- MySQL esté corriendo"
    echo "- La base de datos exista"
    echo "- Las credenciales en .env sean correctas"
    echo ""
    exit 1
fi

echo ""
echo "[5/7] Ejecutando seeders..."
php artisan db:seed

echo ""
echo "[6/7] Limpiando cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear

echo ""
echo "[7/7] Instalación completada!"
echo ""
echo "========================================"
echo "Para iniciar el servidor, ejecuta:"
echo "php artisan serve"
echo "========================================"
echo ""
echo "Credenciales por defecto:"
echo "Admin: admin@riskcontrol.com / password123"
echo "Trader: trader@riskcontrol.com / password123"
echo ""
