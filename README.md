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
