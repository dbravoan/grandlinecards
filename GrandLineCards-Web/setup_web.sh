#!/bin/bash
set -e

# Setup script for Grand Line Cards (Web)
# Uses a temporary Docker container to install dependencies without local PHP.

PROJECT_DIR="GrandLineCards-Web"

echo "🏴‍☠️  Iniciando Setup Robusto de Grand Line Cards..."

# Ensure we are in the right directory or move into it
if [ -d "$PROJECT_DIR" ]; then
    cd "$PROJECT_DIR"
fi

echo "📂 Directorio actual: $(pwd)"

# 0. NUCLEAR PERMISSION FIX
# We reclaim ownership of all files (in case root created them) and open up storage.
echo "🔧 SOLUCIÓN DE PERMISOS (Requiere contraseña sudo)..."
[ -d node_modules ] || mkdir node_modules
sudo chown -R "$(id -u):$(id -g)" .
# Fix NPM Permission Hell
[ -f package-lock.json ] || touch package-lock.json
sudo chmod 777 package-lock.json

sudo chmod -R 777 storage bootstrap/cache config database public node_modules
[ -f .env ] || cp .env.example .env
sudo chmod 666 .env

# 1. Install Dependencies & Fix Permissions using a pure Docker container
# We map the current user to the container user to avoid permission hell.
echo "🐳 Iniciando contenedor constructor (Composer)..."
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -e HOME=/tmp \
    -e COMPOSER_HOME=/tmp \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    bash -c "git config --global --add safe.directory /var/www/html && rm -f composer.lock && composer update && php artisan package:discover"

# 2. Start Sail (Now that we have vendor installed correctly)
echo "⛵ Levantando entorno Sail..."
./vendor/bin/sail down -v # Cleanup old volumes just in case
./vendor/bin/sail up -d

echo "⏳ Esperando 15s a MySQL..."
sleep 15

# 3. Application Setup inside Sail
echo "⚙️  Ejecutando configuración interna..."

# Fix permissions for storage (Sail might need this if user mapping was strict)
# We use 777 inside to be absolutely sure.
./vendor/bin/sail exec -u root laravel.test chmod -R 777 storage bootstrap/cache config database public node_modules
./vendor/bin/sail exec -u root laravel.test chown -R sail:sail storage bootstrap/cache config database public node_modules

# Migrations & Keys

# Migrations & Keys
# Cleanup any specific oauth migrations published by accident during failed runs
rm -f database/migrations/*oauth*.php

# Re-publish fresh migrations to ensure tables exist
./vendor/bin/sail artisan vendor:publish --tag=passport-migrations

./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --force
./vendor/bin/sail artisan db:seed --force

# Passport Setup (Manual to avoid duplicate migration errors)
# We generate keys and the personal access client explicitly.
./vendor/bin/sail artisan passport:keys --force
./vendor/bin/sail artisan passport:client --personal --no-interaction || true # Ignore if already exists

# 4. Frontend Build
echo "🎨 Compilando Frontend..."
./vendor/bin/sail npm install
./vendor/bin/sail npm run build

echo "✅ ✅ LISTO! Accede a http://localhost"
