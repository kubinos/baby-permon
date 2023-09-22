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
