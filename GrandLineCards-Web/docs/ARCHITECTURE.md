# Grand Line Cards - Architecture Overview

## System Overview
Grand Line Cards is a **Laravel 10** monolithic application using **Inertia.js** with **Vue 3** for the frontend. It follows a **Domain-Driven Design (DDD)** approach for complex modules like "Catalog Ingestion".

## Tech Stack
-   **Backend**: Laravel 10 (PHP 8.2)
-   **Frontend**: Vue 3 (Composition API) + TailwindCSS
-   **Database**: MySQL 8.0
-   **Queue/Cache**: Redis
-   **Search**: MySQL (Fuzzy/Like)

## Core Modules

### 1. Catalog Ingestion (DDD)
Located in `src/Catalog/Ingestion`.
-   **Domain**: Defines contracts (`CardSourceInterface`) and DTOs (`RawCardData`).
-   **Application**: Services (`IngestionService`) and Actions (`IngestCardAction`).
-   **Infrastructure**: Implementations (`OfficialSiteScraper`, `JsonFileSource`, `OpenAITranslator`).

### 2. Blog & Content (Standard MVC)
Located in `app/Http/Controllers/Web/Admin`.
-   **Models**: `Post`, `Category`, `Tag`.
-   **Features**: Polymorphic tagging, localized content (`PostTranslation`).

### 3. Pirate Academy
Located in `resources/js/Pages/Academy`.
-   **Components**: Interactive implementation of game mechanics (`BattlefieldMap`, `CombatSimulator`).

## Directory Structure
```
GrandLineCards-Web/
├── app/                  # Standard Laravel App (Admin/Front Controllers)
├── src/                  # DDD Modules
│   └── Catalog/
│       └── Ingestion/    # Scraper & Import Logic
├── resources/
│   └── js/               # Vue 3 Frontend
│       ├── Layouts/      # Admin vs App Layouts
│       └── Pages/        # Inertia Pages
└── routes/
    ├── web.php           # UI Routes
    └── api.php           # Mobile App Routes
```
