# TechStore API

A REST API for managing an electronics store's product catalogue, built with **Laravel 12**
and **Laravel Sanctum** token authentication, and deployed to **Microsoft Azure App Service**.

Anyone can browse products; creating, updating and deleting them requires a bearer token
issued at login.

---

## Stack

| Layer | Choice |
|---|---|
| Framework | Laravel 12 (PHP 8.2+) |
| Auth | Laravel Sanctum — personal access tokens |
| Database | MySQL |
| File storage | Laravel `public` disk for product images |
| Hosting | Azure App Service, deployed from this repository |

## Endpoints

All routes are prefixed with `/api`.

| Method | Route | Auth | Description |
|---|---|---|---|
| `POST` | `/login` | — | Exchange email + password for a bearer token |
| `GET` | `/products` | — | List all products |
| `GET` | `/products/{id}` | — | Get one product (`404` if missing) |
| `POST` | `/products` | Bearer | Create a product; accepts an optional `image` file |
| `PUT` | `/products/{id}` | Bearer | Update a product; accepts an optional new `image` |
| `DELETE` | `/products/{id}` | Bearer | Delete a product |
| `POST` | `/logout` | Bearer | Revoke the current token |

### Example

```bash
# 1. Log in and keep the token
curl -X POST https://<your-app>/api/login \
  -H "Accept: application/json" \
  -d email=admin@example.com -d password=********

# 2. Create a product with it
curl -X POST https://<your-app>/api/products \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <token>" \
  -F name="Wireless Headphones" -F brand="Sony" -F category="Audio" \
  -F price=249.00 -F stock=40 -F image=@headphones.jpg
```

## Data model

`products`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint | primary key |
| `name` | string | required |
| `brand` | string | nullable |
| `category` | string | nullable |
| `price` | decimal(8,2) | required |
| `stock` | integer | defaults to `0` |
| `description` | text | nullable |
| `image` | string | nullable — relative path on the `public` disk |
| `created_at` / `updated_at` | timestamps | |

Mass assignment is restricted to those columns through the model's `$fillable` list.
Sanctum's `personal_access_tokens` table stores issued tokens.

## Running locally

```bash
cp .env.example .env          # then set DB_DATABASE / DB_USERNAME / DB_PASSWORD
php artisan key:generate
php artisan migrate
php artisan storage:link      # serve uploaded product images
php artisan db:seed --class=ProductSeeder   # optional: 30 sample products
php artisan serve
```

Create your own user with `php artisan tinker` rather than relying on seeded credentials.

## Deployment

The app is deployed to Azure App Service straight from this repository. `.deployment`
runs `deploy.sh`, which creates Laravel's storage and cache directories and sets their
permissions. `web.config` routes requests through `public/index.php`.

`vendor/` is committed on purpose: the App Service deployment environment used here has no
Composer, so dependencies ship with the code. A cleaner setup would build `vendor/` in a
GitHub Actions workflow and deploy the artifact, keeping it out of version control.

## Roadmap

- Request validation for create and update (Form Requests), returning `422` on bad input
- Rate limiting on `/login`
- Delete the stored image when a product is deleted or its image replaced
- Pagination and filtering (by category, brand, price range) on `GET /products`
- Feature tests for the auth flow and each endpoint
- CI build in GitHub Actions so `vendor/` can leave the repository

## Author

**Abdulrahman Alrashidi** — [Portfolio](https://alrashidi-25.github.io) ·
[LinkedIn](https://www.linkedin.com/in/abdulrahman-alrashidi-9b9543249/) ·
[GitHub](https://github.com/Alrashidi-25)
