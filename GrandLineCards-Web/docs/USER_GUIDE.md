# Grand Line Cards - User Guide

## For Administrators (Backoffice)

### Access
Navigate to `/dbadmin/login`. Default credentials in development are `admin@grandlinecards.test` / `password`.

### Managing Content
1.  **Blog Posts**:
    -   Go to **Blog Posts**.
    -   Click "Create".
    -   Use the **AI Assist** button to generate excerpts automatically.
    -   Assign Categories and Tags for SEO.
2.  **Taxonomies**:
    -   Go to **Taxonomies** to create new Categories (e.g., "Meta", "News") or Tags.
3.  **Translations**:
    -   Go to **Translations** to review pending card translations.

### Data Ingestion
To update the card catalog:
1.  SSH into the server (or use `docker exec`).
2.  Run `php artisan cards:import --set=OP-05`.

---

## For Players (Frontend)

### Rules Cheat Sheet
Visit `/reglas` for a quick interactive guide during games. Ideal for mobile use.

### Pirate Academy
Visit `/academia` to learn the game mechanics, visualized on a virtual board.

### Deck Builder
Visit `/profile/mazos` (requires login) to build and save your decks.
