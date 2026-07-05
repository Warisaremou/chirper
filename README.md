# Chirper

A blogging app built with Laravel.

## Features

- **Auth** — register, login, logout
- **Chirps** — create, edit, delete posts (max 255 characters)
- **Likes** — like and unlike chirps
- **Follow system** — follow/unfollow users
- **User profiles** — avatar upload, profile settings
- **Search** — find chirps by keyword
- **Pagination** — efficient chirp listing
- **Notifications** — in-app + Web Push notifications via Redis queue (like, follow events)

## Setup

### 1. Clone and install dependencies

```bash
git clone https://github.com/Warisaremou/chirper.git
cd chirper
composer install
npm install
```

### 2. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database and Redis credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=chirper
DB_USERNAME=your_user
DB_PASSWORD=your_password

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=your_redis_password
REDIS_PORT=6379
```

### 3. Start Docker services

```bash
docker compose up -d
```

This starts PostgreSQL, Redis, RedisInsight (`localhost:5540`) and Adminer (`localhost:8080`).

### 4. Database

```bash
php artisan migrate --seed
```

Seeder creates 5 users (password: `password`) and 40–50 chirps.

### 5. Generate VAPID keys (Web Push notifications)

```bash
php artisan webpush:vapid
```

This writes `VAPID_PUBLIC_KEY` and `VAPID_PRIVATE_KEY` to your `.env`.

### 6. Start the dev server

```bash
composer run dev
```

App runs at `http://localhost:8000`

View api doc at `http://localhost:8000/docs/api`