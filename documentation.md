# Grand Line Cards - Documentation

## Project Architecture
- **Framework**: Laravel 12 (PHP 8.3).
- **Frontend**: Vue 3 + Inertia.js + Tailwind CSS.
- **Database**: MySQL 8.
- **Search**: Eloquent + Criteria Pattern (Repository).

## Key Modules

### 1. Catalog (`src/Catalog`)
- **Domain**: Card, Expansion, Rules.
- **Infrastructure**: Spatie Data Transfer Objects, Eloquent Repositories.
- **Features**: Search, Filters (Criteria), OCR (tbd).

### 2. Marketplace (`app/Models/MarketListing`)
- **Features**: Auctions, Fixed Price, Bidding System.
- **Logistics**: "Vault" system for consolidated shipping.
- **Payments**: Stripe Connect (concept).

### 3. Meta Analysis (`app/Services/Meta`)
- **Features**: Deck analysis, Card Usage Statistics, Tier Lists.

### 4. User System
- **Roles**: User, Admin, Verified Seller.
- **Profile**: Address Book, Orders, Sales, Collection.

## Development Setup

### Requirements
- Docker & Docker Compose.
- Node.js 20+.
- PHP 8.3+.

### Installation
1. `git clone ...`
2. `./vendor/bin/sail up -d`
3. `./vendor/bin/sail artisan migrate --seed`
4. `./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev`

### Key Commands
- **Import Cards**: `./vendor/bin/sail artisan cards:import` (Scrapes official site).
- **Run Tests**: `./vendor/bin/sail artisan test`.

## Directory Structure
- `app/`: Laravel Models & Controllers (Web/Api).
- `src/`: DDD Bounded Contexts (Catalog).
- `resources/js/`: Vue Pages & Components.
- `database/`: Migrations & Seeders.
