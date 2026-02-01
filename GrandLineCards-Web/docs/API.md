# Grand Line Cards - API Reference

## Base URL
`https://grandlinecards.com/api` (Production)
`http://localhost/api` (Development)

## Authentication
Authentication is currently **Token-based** (Sanctum) for write operations.
Public read operations do not require authentication.

## Endpoints

### 1. Card Search
Search for cards by ID, Name, or Effect text.

-   **Endpoint**: `GET /search/cards`
-   **Query Query Parameters**:
    -   `query`: (string, required) Text to search for (min 2 chars).
-   **Response**:
    ```json
    [
        {
            "id": "OP01-001",
            "name": "Roronoa Zoro",
            "image": "https://.../OP01-001.png",
            "rarity": "L",
            "color": "Red",
            "type": "Leader",
            "power": 5000
        }
    ]
    ```

### 2. Decks (v1)
-   **Endpoint**: `GET /v1/decks`
-   **Description**: List public community decks.

### 3. Event Suggestions (v1)
-   **Endpoint**: `POST /v1/event-suggestions`
-   **Auth**: Required (Bearer Token)
-   **Body**:
    ```json
    {
        "title": "Local Tournament",
        "date": "2026-02-20",
        "location": "Madrid"
    }
    ```
