# Merchant Notes App

**Merchant Notes App** is a full-stack containerized web application that allows users to manage notes related to merchants.  
It features a Vue 3 frontend with Vite and Pinia, and a Laravel 12 backend.  
The app is designed for quick local development using Docker and Laravel Sail.

---

## Features

- Create and manage notes associated with merchants
- RESTful Laravel backend
- Vue 3 with Vite for fast hot module replacement
- Dockerized with Laravel Sail for consistent dev environments
- State management using Pinia

---

## Prerequisites

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Git](https://git-scm.com/)

---

## Installation & Setup

```bash
# Clone repo
git clone git@github.com:your-org/merchants-notes-app.git
cd merchants-notes-app
cp .env.example .env

# Backend folder
cd backend
composer install 
cp .env.example .env

# Run sail command from project root
cd ..
./backend/vendor/bin/sail up -d
./backend/vendor/bin/sail php artisan key:generate
```

---

## Running Migrations and seeder

```bash
./backend/vendor/bin/sail artisan migrate --seed
```

---

## Installing frontend dependencies

```bash
cd frontend 
npm install
```

---

## Accessing the App

- Laravel backend: [http://localhost](http://localhost)
- Vue frontend (Vite): [http://localhost:5173](http://localhost:5173)

---

## Running Tests

```bash
./vendor/bin/sail test
```

---

## Project Structure

```
merchant-notes-app/
├── backend/              # Laravel backend
├── frontend/             # Vue.js frontend
├── .env                  # Environment config for docker compose
├── docker-compose.yml    # Docker services definition
└── README.md             # Project instructions
```

---
