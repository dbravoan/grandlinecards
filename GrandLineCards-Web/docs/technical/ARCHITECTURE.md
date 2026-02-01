# Arquitectura Técnica - Grand Line Cards

## Filosofía: Clean Architecture + DDD
Este proyecto no sigue la estructura MVC tradicional de Laravel. Utilizamos **Domain-Driven Design (DDD)** para desacoplar la lógica de negocio del framework.

### Estructura de Directorios (`src/`)
El código del sistema vive en `src/`, organizado por **Bounded Contexts** (Contextos Delimitados).

```text
src/
├── Catalog/             # Contexto: Catálogo de Cartas
│   ├── Cards/           # Módulo: Cartas
│   │   ├── Domain/      # Reglas de Negocio Puras
│   │   │   ├── Card.php (Entity)
│   │   │   └── CardRepositoryInterface.php
│   │   ├── Application/ # Casos de Uso
│   │   │   └── GetCardDetailsAction.php
│   │   └── Infrastructure/ # Implementación Técnica
│   │       └── EloquentCardRepository.php
│   └── Expansions/      # Módulo: Sets/Expansiones
├── Marketplace/         # Contexto: Tienda
└── Shared/              # Kernel Compartido
    └── Domain/
        └── ValueObjects/
            └── CardId.php
```

### Capas del Sistema

#### 1. Domain (Dominio)
- **Responsabilidad**: Contiene la lógica de negocio pura y las reglas del juego.
- **Dependencias**: CERO. No depende de Laravel, ni de Eloquent, ni de HTTP.
- **Componentes**: Entities, Value Objects, Domain Events, Repository Interfaces.

#### 2. Application (Aplicación)
- **Responsabilidad**: Orquesta los casos de uso (lo que el usuario "hace").
- **Dependencias**: Solo de `Domain`.
- **Componentes**: Actions/UseCases, DTOs.

#### 3. Infrastructure (Infraestructura)
- **Responsabilidad**: Implementa las interfaces del dominio usando herramientas concretas.
- **Dependencias**: Framework (Laravel), Base de Datos, APIs externas.
- **Componentes**: Eloquent Models, Mailers, API Clients.

### Inyección de Dependencias
Laravel se encarga de "pegar" las capas mediante Service Providers.
Ver: `app/Providers/DomainServiceProvider.php`.

```php
// Decimos a Laravel: "Cuando alguien pida CardRepositoryInterface, dale EloquentCardRepository"
$this->bindings = [
    CardRepositoryInterface::class => EloquentCardRepository::class,
];
```

### 4. Data Engineering (Scraping & Ingesta)
Este módulo se encarga de poblar y mantener actualizada la base de datos de cartas.

- **Ubicación**: `app/Services/Scraper` y `app/Console/Commands`.
- **Componentes Principales**:
  - **ScraperService**: Fachada que orquesta el proceso.
  - **Sources/OfficialSourceScraper**: Extrae datos de la web oficial.
  - **ImageProcessor**: Descarga, redimensiona y convierte imágenes a WebP.
  - **TranslatorService**: Usa OpenAI (GPT-4o) para traducciones y extracción de keywords.

#### Flujo de Ingesta
1. **Fetch**: Se descarga el HTML o JSON de la fuente.
2. **Parse**: Se extraen los datos crudos (DTOs).
3. **Enrich**: Se traducen textos y se procesan imágenes.
4. **Persist**: Se guarda en BD (Upsert) y Storage.
