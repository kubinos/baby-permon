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

## Simple documentation

Every response with resources is wrapped in root `data` key.

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
  "number": 1
}
```

#### Request item (3,4)

```json
{
  "name": "Sound name",
  "number": 1
}
```
