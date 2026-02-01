# 🏴‍☠️ Grand Line Cards - La Plataforma del Rey de los Piratas

> Base de datos, Traducciones y Comunidad para One Piece Card Game (OPCG) en Español.

![Grand Line Cards Banner](https://via.placeholder.com/1200x300?text=Grand+Line+Cards+Platform)

## 📋 Sobre el Proyecto

**Grand Line Cards** es un ecosistema digital diseñado para eliminar la barrera del idioma en el juego de cartas de One Piece. Consta de dos aplicaciones principales:

1.  **Web Platform (`GrandLineCards-Web`)**:
    *   **Tech**: Laravel 12 (v11.x), Inertia.js, Vue 3, Tailwind CSS.
    *   **Rol**: Hub central, API Server, Administración, Wiki y Tienda.
    *   **Arquitectura**: Clean Architecture (DDD).

2.  **Mobile App (`GrandLineCards-App`)**:
    *   **Tech**: Ionic Framework 8, Vue 3, Capacitor.
    *   **Rol**: Herramienta de bolsillo para consulta rápida en torneos y Deck Builder offline-first.

### 🛡️ Seguridad & Performance
-   **Security**: A+ Headers (CSP, HSTS, X-Frame) via `SecurityHeadersMiddleware`.
-   **Smart SEO**: URL rewriting automática (`/cartas/op01/rojo`) con Meta tags dinámicos.
-   **Optimización**: Compresión automática de imágenes (WebP) y Asset Hashing con Vite.

---

## 🚀 Inicio Rápido (Local Development)

### Requisitos Previos
*   Docker & Docker Compose (Recomendado via Laravel Sail).
*   Node.js v20+.
*   PHP 8.3 (si no usas Docker).

### 1. Backend & Web (Laravel)
El corazón del sistema. Debes levantar esto primero para tener la API.

```bash
cd GrandLineCards-Web
# Instalar dependencias (usando contenedor temporal si no tienes PHP local)
docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs

# Iniciar Sail (Docker)
./vendor/bin/sail up -d

# Ejecutar Migraciones y Seeds
./vendor/bin/sail artisan migrate --seed

# Compilar Assets (En otra terminal)
npm install && npm run dev
```

La web estará disponible en: `http://localhost`

### 2. Mobile App (Ionic)
La aplicación cliente.

```bash
cd GrandLineCards-App
# Instalar dependencias
npm install

# Ejecutar en modo desarrollo (navegador)
ionic serve
```

La app se abrirá en: `http://localhost:8100`

---
    
## 🔑 Access & Credentials

Once the seeders have run (`php artisan migrate --seed`), you can use the following default credentials:

### 🏴‍☠️ User Panel (Frontoffice)
-   **URL**: `http://localhost/login` (or clicking "Ingresar" on home)
-   **User 1**: `capitan@grandline.com` / `password` (Verified)
-   **User 2**: `user@grandlinecards.es` / `password` (Verified)

### ⚓ Admin Panel (Backoffice)
-   **URL**: `http://localhost/dbadmin/login` (Secure Route)
-   **Admin**: `admin@grandline.com` / `password`
-   **Note**: The backoffice is completely separated from the frontoffice authentication. You must log out as a user to log in as an admin, or use an Incognito window.

---

## 📚 Documentación Técnica

Hemos preparado guías detalladas para desarrolladores en la carpeta `docs/`:

*   [📘 Arquitectura y DDD](docs/technical/ARCHITECTURE.md): Entiende cómo organizamos el código en `src/`.
*   [🚀 Despliegue y SEO](docs/strategy/deployment_strategy.md): Estrategia de DevOps.
*   [📢 Marketing](docs/strategy/marketing_strategy.md): Plan de crecimiento y SEO ("Google One Piece").
*   [📡 API Reference (Swagger)](docs/api/openapi.yaml): Especificación completa de los endpoints.

## 🧪 Testing

Para ejecutar la suite de pruebas del Backend:

```bash
# Desde GrandLineCards-Web
./vendor/bin/sail artisan test
```

## 🤝 Contribución

1.  Usa **Conventional Commits** (ej: `feat: add nami card entity`).
2.  Respeta las capas del Dominio. No pongas lógica de negocio en los controladores.
3.  ¡Disfruta el viaje, Nakama!
