# Flight Management API

A Laravel 12 application for managing flights, built with DDD, Clean Architecture, and async queue processing via Laravel Horizon and Redis.

---

## Getting Started

```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

The API will be available at **http://localhost**.

---

## Authentication

All endpoints are protected with an API key header:

```
Api-Key: your-api-key
```

The key is configured via `API_KEY` in your `.env` file.

---

## Laravel Horizon

Queue management is handled by Laravel Horizon backed by Redis.

Access the dashboard at:

```
http://localhost/horizon
```

Horizon runs automatically as a dedicated Docker service when you run `sail up`.

---

## API Endpoints

### Create Flight

**POST** `/api/flights`

**Headers:**
```
Api-Key: your-api-key
Content-Type: application/json
```

**Request:**
```json
{
    "legs": [
        {
            "segments": [
                {
                    "origin": "BCN",
                    "destination": "LON",
                    "departure": "2026-06-09T06:45:00",
                    "arrival": "2026-06-09T10:55:00",
                    "cabinClass": "Y",
                    "airline": "UA",
                    "flightNumber": "101"
                },
                {
                    "origin": "LON",
                    "destination": "JFK",
                    "departure": "2026-06-09T11:55:00",
                    "arrival": "2026-06-09T14:55:00",
                    "cabinClass": "Y",
                    "airline": "UA",
                    "flightNumber": "102"
                }
            ]
        },
        {
            "segments": [
                {
                    "origin": "JFK",
                    "destination": "LON",
                    "departure": "2026-06-25T06:45:00",
                    "arrival": "2026-06-25T10:55:00",
                    "cabinClass": "Y",
                    "airline": "UA",
                    "flightNumber": "101"
                },
                {
                    "origin": "LON",
                    "destination": "BCN",
                    "departure": "2026-06-25T11:55:00",
                    "arrival": "2026-06-25T13:55:00",
                    "cabinClass": "Y",
                    "airline": "UA",
                    "flightNumber": "102"
                }
            ]
        }
    ]
}
```

**Response** `201 Created`:
```json
{
    "flightId": "550e8400-e29b-41d4-a716-446655440000"
}
```

---

### Update Flight

**PUT** `/api/flights/{flightId}`

The update is processed **asynchronously** via a queued job. The `Idempotency-Key` header is **required** to prevent duplicate updates on retries or concurrent submissions — use a unique key per update operation and a fresh key for each new update.

**Headers:**
```
Api-Key: your-api-key
Content-Type: application/json
Idempotency-Key: unique-key-per-request
```

**Request:**
```json
{
    "legs": [
        {
            "segments": [
                {
                    "origin": "BCN",
                    "destination": "LON",
                    "departure": "2026-06-09T06:40:00",
                    "arrival": "2026-06-09T10:50:00",
                    "cabinClass": "Y",
                    "airline": "UA",
                    "flightNumber": "101"
                },
                {
                    "origin": "LON",
                    "destination": "JFK",
                    "departure": "2026-06-09T11:55:00",
                    "arrival": "2026-06-09T14:55:00",
                    "cabinClass": "Y",
                    "airline": "UA",
                    "flightNumber": "102"
                }
            ]
        }
    ]
}
```

**Response** `204 No Content`

> **Note:** Only the legs included in the request will be updated. Each leg must include all its segments with full field data.

**Idempotency behaviour:**

| Scenario | Response |
|---|---|
| First request with a new key | `204` — job dispatched |
| Same key while job is processing | `409` — already in progress |
| Same key after job has completed | `409` — key already used, send a new key |
| Same key after job fails all retries | `204` — key released, job re-dispatched |

---

### Get Flight

**GET** `/api/flights/{flightId}`

**Headers:**
```
Api-Key: your-api-key
```

**Response** `200 OK`:
```json
{
    "data": {
        "flightId": "550e8400-e29b-41d4-a716-446655440000",
        "status": "scheduled",
        "legs": [
            {
                "legIndex": 0,
                "segments": [
                    {
                        "segmentIndex": 0,
                        "origin": "BCN",
                        "destination": "LON",
                        "departure": "2026-06-09T06:45:00",
                        "arrival": "2026-06-09T10:55:00",
                        "cabinClass": "Y",
                        "airline": "UA",
                        "flightNumber": "101"
                    },
                    {
                        "segmentIndex": 1,
                        "origin": "LON",
                        "destination": "JFK",
                        "departure": "2026-06-09T11:55:00",
                        "arrival": "2026-06-09T14:55:00",
                        "cabinClass": "Y",
                        "airline": "UA",
                        "flightNumber": "102"
                    }
                ]
            }
        ]
    }
}
```

---

## Cabin Class Codes

| Code | Class |
|---|---|
| `Y` | Economy |
| `W` | Premium Economy |
| `J` | Business |
| `F` | First |
