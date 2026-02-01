# PRD: Grand Line Cards - Master Development Plan

## Overview
Advance the "Grand Line Cards" platform from prototype to production-ready distribution platform.

## Task 0: Data Ingestion & Assets
**Priority: High (Blocker)**
Implement robust card data and image ingestion.
- **Goal**: Download all cards from official source, INCLUDING images.
- **Input**: Official Site Scraper.
- **Output**: Database populated with cards, images saved locally (public/storage), URLs updated to local paths.
- **Details**: 
    - Modify `ImportCardsCommand` or Scraper to download images.
    - Store images in `storage/app/public/cards/{set}/{id}.png`.
    - Run full import for all sets.

## Task 1: Admin Authentication Refinement
**Priority: High (Security)**
Ensure distinct login flows for Users and Admins.
- **Goal**: Separate logic and UI for Admin vs User login.
- **Middleware**: Ensure `admin` guard/middleware is strictly applied.
- **UI**: Admin = Terminal/Hacker style. User = Premium Nautical style.

## Task 2: Rules Cheat Sheet (/reglas)
**Priority: Medium**
Interactive rules guide for new players.
- **Goal**: Semantic, mobile-first rules page.
- **Sections**: Setup, Turn Flow, Combat, Keywords.
- **Design**: "Dark Mode Nautical", sticky nav.

## Task 3: Localization Hardening
**Priority: Medium**
Full ES/EN localization for SEO.
- **Goal**: Translate all public URIs (`/cartas`, `/tienda`) and content.
- **Details**: Audit `routes/web.php`, `PostTranslation`, `CardTranslation`.

## Task 4: Pirate Academy (/academia)
**Priority: Medium**
Onboarding guide and deck builder entry point.
- **Features**: Visual map of battle board, "Your First Step" CTA.

## Task 5: OCR Card Search
**Priority: Low (Feature)**
Mobile app feature to scan cards.
- **Details**: Ionic integration, OCR logic, backend search endpoint.

## Task 6: Blog & SEO
**Priority: Medium**
Content marketing engine.
- **Details**: Categories, Tags, Polymorphic relationships.


## Task 7: Architecture Stabilization
**Priority: Low (Maintenance)**
Fix npm errors, refactor controllers (`Web\Front` vs `Web\Admin`).

## Task 8: Ingestion Refactoring
**Priority: Low (Maintenance)**
Refactor ingestion logic to use new contracts and services.

## Task 9: Create post redactor service.
**Priority: Low (Maintenance)**
Create a service to redact posts with AI.

## Task 10: Review database data.
**Priority: Low (Maintenance)**
Review database data and fix any issues. Complete the review and update the database. review records generate translations.

## Task 11: Review translations.
**Priority: Low (Maintenance)**
Review translations and fix any issues. Complete the review and update the translations.

## Task 12: Create complete documentation.
**Priority: Low (Maintenance)**
Create complete documentation for the project.

## Task 13: Create complete marketing strategy.
**Priority: Low (Maintenance)**
Create complete marketing strategy for the project.

## Task 14: Create complete deployment strategy.
**Priority: Low (Maintenance)**
Create complete deployment strategy for the project. Step by step guide to deploy in production VPS.

## Task 15: Price Intelligence Engine
**Status: Completed**
**Priority: High (Value Add)**
Scrape and track card prices from multiple sources.
- **Goal**: Historical price tracking and "Best Price" finding.
- **Sources**: Cardmarket, TCGPlayer, eBay (sold), Local Stores.
- **Features**:
    -   Daily scans.
    -   Mean price calculation.
    -   Link to external store (Affiliate) or Internal Store if in stock.

## Task 16: Marketplace & Smart Logistics
**Status: Completed**
**Priority: High (Differentiation)**
Enable P2P trading with "Smart Consolidation" to save shipping.
- **Market Types**: Fixed Price, Auctions.
- **Inventory**: Company Stock (Boosters/Boxes) + User Listings (Singles).
- **Logistics**: "Grand Line Vault".
    -   **Direct**: 1 card -> Seller ships to Buyer.
    -   **Consolidated**: Buyer buys 10 cards from 10 sellers -> Sellers ship to Warehouse -> We bundle -> Ship 1 box to Buyer.
    -   **Seller Incentive**: Zero shipping cost for Seller if shipping to Warehouse (we cover or subsidize via buyer fees).

## Task 19: Global QA & 404 Review
**Status: Completed**
**Priority: High (Quality Assurance)**
Review all pages and assets to ensure no broken links or missing images.
- **Goal**: Zero 404 errors on pages and images.
- **Scope**:
    -   Check all main navigation routes.
    -   Check Card Detail pages (images loaded via Storage).
    -   Check Admin Dashboard and sub-pages.
    -   Verify error pages (custom 404/500).



## Task 47: Fix DeckBuilder Vue Syntax Error
**Priority: High (Bug)**
Resolve syntax error in `DeckBuilder.vue`.

## Task 48: Fix Logistics Index Import Path
**Priority: High (Bug)**
Fix import path issue in `Logistics/Index.vue`.

## Task 49: Restore Missing Modal & UI Components
**Priority: High (Bug)**
Restore `Modal.vue` and other missing UI components.

## Task 50: Debug Card Detail Data Loading
**Priority: High (Bug)**
Fix data loading for specific card variants.

## Task 51: Friendly URLs v1 (Basic)
**Priority: High (SEO)**
Implement basic friendly URLs for Sets and Colors.

## Task 52: Debug Empty Info
**Priority: High (Bug)**
Fix issue with CardResource wrapping data.

## Task 53: Shop Page Errors
**Priority: High (Bug)**
Fix JS errors and 404s on Shop page.

## Task 54: Missing Translations (Hotfix)
**Priority: High (Bug)**
Fix missing Spanish translations for reported cards.

## Task 55: SEO & Localization 2.0 (The Indexing Algorithm)
**Priority: Critical (Growth)**
Execute a comprehensive overhaul of URLs and Content for perfect indexing.
- **Algorithm**: Create a smart route resolver to handle ANY combination of filters as friendly URLs.
    - Example: `/cartas/rojo-verde/coste-4/op01` works.
    - Example: `/cartas/op01/rojo` works.
    - Logic: Slug analysis -> determine if slug is Color, Set, Rarity, etc. -> Apply filters.
- **Dynamic Content**: Generate unique H1, Title, and Description for each combination.
    - "Cartas Rojas y Verdes de Coste 4 - One Piece TCG | Grand Line Cards"
- **Multilingual**: Ensure site is native Spanish but supports English fallback fully.
- **Card Translations**: Systematically verify and translate ALL cards (AI assisted).

## Task 56: Ralph Loop - Continuous Perfection
**Priority: Critical (Process)**
Agently iterate through all tasks until completion.
