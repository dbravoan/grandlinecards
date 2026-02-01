# Guía de Despliegue y SEO

## Integración Continua (CI/CD)
El proyecto utiliza **GitHub Actions** para el despliegue automático.
Archivo: `.github/workflows/deploy.yml`

### Flujo de Despliegue
1. **Trigger**: Push a la rama `main`.
2. **Build**:
   - Instala dependencias PHP (`composer install`).
   - Compila assets Frontend (`npm run build`).
3. **Deploy**:
   - Empaqueta el proyecto en un `.tar.gz`.
   - Lo envía vía SCP al servidor (VPS).
   - Ejecuta comandos remotos vía SSH: `migrate`, `cache:clear`, `php-fpm reload`.

### Requisitos del Servidor
- **OS**: Ubuntu 22.04 / 24.04 (Recomendado).
- **Web Server**: Nginx.
- **PHP**: 8.3 (Extensiones: xml, dom, mbstring, mysql, redis).
- **Database**: MySQL 8.0+.
- **Cache**: Redis (Crítico para rendimiento de consultas de cartas).
- **Node**: v20+ (para build de assets, no necesario en runtime si se suben compilados).

---

## Estrategia SEO
El SEO es vital para el objetivo de "Wikipedia de One Piece TCG".

### 1. Renderizado (SSR)
Usamos **Inertia.js SSR** (Server-Side Rendering). Esto permite que los bots de Google lean el HTML completo al cargar la página, en lugar de esperar a que Vue.js renderice el contenido en el cliente.

### 2. Meta Tags Dinámicos
Implementamos un `SeoServiceProvider` que inyecta una variable `$meta` global a todas las vistas Blade.

**Uso en Controladores:**
```php
// CardController.php
public function show($id) {
    seo()->setTitle($card->name . ' (Español) - Grand Line Cards');
    seo()->setDescription("Traducción y reglas para {$card->name}. Coste: {$card->cost}...");
    seo()->setImage($card->image_url);
    
    return Inertia::render('Catalog/CardDetail', [...]);
}
```

### 3. Schema.org (JSON-LD)
Cada ficha de carta incluye datos estructurados de tipo `Product`:
- `name`: Nombre de la carta.
- `image`: URL oficial.
- `description`: Efecto traducido.
- `offers`: Precio de mercado (si disponible).

## Despliegue del Sistema de Ingesta de Datos

Para mantener la base de datos de cartas actualizada, es necesario configurar la ejecución periódica del scraper y asegurar las credenciales correctas.

### 1. Variables de Entorno (.env)
Asegúrate de configurar las siguientes claves en producción:

```bash
# Scraper Configuration
OPENAI_API_KEY=sk-proj-... # Token de OpenAI para traducciones
SCRAPER_USER_AGENT="GrandLineCardsBot/1.0 (+https://grandlinecards.com)"
```

### 2. Programación de Tareas (Cron)
Laravel Schedule se encarga de ejecutar el comando de ingesta. En el servidor, añade esta entrada al crontab del usuario (normalmente `www-data` o `forge`):

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Luego, define la frecuencia en `app/Console/Kernel.php` (o `routes/console.php` en Laravel 11+):

```php
// Ejecutar diariamente a las 03:00 AM para evitar tráfico alto
Schedule::command('cards:import --source=official')->dailyAt('03:00');
```

### 3. Colas (Queues)
El procesamiento de imágenes y llamadas a OpenAI puede ser lento. Se recomienda usar colas para estas tareas si el volumen es alto.
- Ejecutar worker: `php artisan queue:work --timeout=60`
- Monitorizar con **Horizon** (si usas Redis).

### 4. Permisos de Storage
El usuario del web server debe tener permisos de escritura en la carpeta donde se descargan las imágenes:

```bash
chmod -R 775 storage/app/public/cards
chown -R www-data:www-data storage/app/public/cards
```
