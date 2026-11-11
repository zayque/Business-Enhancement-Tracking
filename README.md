# BE Tracker — Business Enhancement Tracking

A clean, modern workspace for tracking business enhancement (BE) requests from idea to rollout:
a seven-stage delivery pipeline, RFS / rollout dates, and man-day cost tracking.

## Features

- **Dashboard** with total cost, job counts, a status pipeline chart and a developer workload chart
- **Jobs table** with search, status filter chips, expandable detail rows and bulk delete
- **Single job form** for create and edit, with a status stepper, live cost calculation (RM 800 per internal man-day) and status-dependent required fields
- **Light and dark themes**, responsive layout
- **Security:** prepared statements, escaped output, CSRF tokens and hashed passwords (legacy plain-text passwords are upgraded automatically on first login)

## Quick start (Docker)

Requires Docker Desktop.

```bash
docker compose up -d --build
```

| Service  | URL                      | Notes                                   |
|----------|--------------------------|-----------------------------------------|
| App      | http://localhost:8080    | PHP 8.3 + Apache                        |
| Adminer  | http://localhost:8081    | DB admin UI (server `db`, user `betracker` / `betracker`) |
| MariaDB  | `localhost:3307`         | Database `be_tracking`                  |

When the database volume is first created, the SQL files in [`database/`](database/) seed it.
To reset the data: `docker compose down -v && docker compose up -d`.

Copy `.env.example` to `.env` to change ports or credentials.

### Run PHP locally, with only the database in Docker

```bash
docker compose up -d db
php -S localhost:8000
```

The app defaults to `127.0.0.1:3307` with user `betracker`, so it connects to the Docker DB without extra configuration.
Override this with the `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` and `DB_PASS` environment variables.

## Demo login

| Staff ID  | Password |
|-----------|----------|
| `1234abn` | `adjn21` |

## Project structure

```
index.php            Sign-in page
dashboard.php        Dashboard + jobs table
job_new.php          Create job
job_edit.php         Edit job
job_delete.php       Delete one or many jobs (POST)
logout.php
includes/            Bootstrap (DB, session, helpers), job domain logic, layout, shared form
assets/              CSS, JS, images
database/            Schema + seed data (Docker init scripts)
```
