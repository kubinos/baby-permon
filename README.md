# Baby Permon

> Command reference

## Backend

```bash
# Run migrations
$ php artisan migrate

# Run seeders
$ php artisan db:seed

# Recreate db with seeding
$ php artisan migrate:fresh --seed
# -- or --
$ make db-dev

# Rollback migrations
$ php artisan migrate:rollback

# Clear cache
$ php artisan o:c
```

## Frontend

```bash
# Run with hot reload
$ yarn dev

# Build for production
$ yarn build
```

Build output is located in `public/build` directory.

## Simple endpoint documentation

Every response with resources is wrapped in root `data` key.

### Level config

1. `[GET]     /api/config/level`
2. `[PUT]     /api/config/level`

#### Response and request item

```json
{
    "level_1": 10,
    "level_2": 20,
    "level_3": 30
}
```

### Sound

1. `[GET]     /api/sounds`
2. `[GET]     /api/sounds/{soundId}`
3. `[POST]    /api/sounds`
4. `[PUT]     /api/sounds/{soundId}`
5. `[DELETE]  /api/sounds/{soundId}`

#### Response item (1,2,3)

```json
{
    "id": 1,
    "name": "Sound name",
    "number": "2.wav"
}
```

#### Request item (3,4)

```json
{
    "name": "Sound name",
    "number": "2.wav"
}
```

### Station

1. `[GET]     /api/stations`
2. `[GET]     /api/stations/{stationId}`
3. `[POST]    /api/stations`
4. `[PUT]     /api/stations/{stationId}`
5. `[DELETE]  /api/stations/{stationId}`

#### Response item (1,2,3)

```json
{
    "id": 1,
    "name": "Sound name",
    "location": "under-world"
}
```

#### Request item (3,4)

```json
{
    "name": "Sound name",
    "location": "under-world"
}
```

### Task

1. `[GET]     /api/tasks`
2. `[GET]     /api/tasks/{taskId}`
3. `[POST]    /api/tasks`
4. `[PUT]     /api/tasks/{taskId}`
5. `[DELETE]  /api/tasks/{taskId}`

#### Response item (1,2,3)

```json
{
    "id": 1,
    "name": "officia",
    "difficulty": 2,
    "station": {
        "id": 1,
        "name": "New Alexis",
        "location": "under-world"
    },
    "soundCs": {
        "id": 1,
        "name": "Tomato",
        "number": "55.wav"
    },
    "soundEn": {
        "id": 2,
        "name": "Bisque",
        "number": "97.wav"
    },
    "soundDe": {
        "id": 3,
        "name": "Purple",
        "number": "64.wav"
    },
    "responseNumber": "2",
    "responseColor": "red",
    "responseShape": "rectangle",
    "pointsCorrect": 9,
    "pointsPartial": 6,
    "pointsIncorrect": 1
}
```

#### Request item (3,4)

```json
{
    "name": "officia",
    "difficulty": 4,
    "stationId": 11,
    "soundCsId": 12,
    "soundEnId": 23,
    "soundDeId": 34,
    "responseNumber": "2",
    "responseColor": "red",
    "responseShape": "rectangle",
    "pointsCorrect": 9,
    "pointsPartial": 6,
    "pointsIncorrect": 1
}
```

### Game

1. `[POST]    /api/games`
2. `[DELETE]  /api/games/{gameChip}`

#### Response item (1)

```json
{
    "id": 1,
    "chip": "1234567890",
    "salutatuion": "Mr.",
    "level": 1,
    "emotion": "happy",
    "language": "cs"
}
```

#### Request item (1)

```json
{
    "chip": "1234567890",
    "salutatuion": "Mrs.",
    "level": 2,
    "emotion": "sad",
    "language": "de"
}
```
