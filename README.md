# Future Studio SaaS

multi-tenant saas backend built with laravel. each tenant gets its own database, separated by domain (`tenant.localhost`). central app handles tenant registration, plans, subscriptions. tenant app handles customers, users, roles/permissions.

## requirements

- docker + docker compose

## setup

```bash
docker compose up -d --build
```

that's it. the entrypoint installs dependencies, runs migrations, and seeds the database automatically. nginx serves on `localhost:8000`.

seeding creates a central admin: `admin@central.com` / `password123`.

## creating a tenant

login as the central admin, then `POST /api/v1/tenants` with `name`, `admin_name`, `admin_email`, `admin_password`. this creates the tenant's database, runs its migrations/seeders, and assigns the admin role to the given admin user automatically.

tenant apis live at `http://{tenant}.localhost:8000/api/v1/...`. locally, `.localhost` subdomains don't auto-resolve on windows — add an entry to your hosts file per tenant, or use `curl --resolve` for testing without touching it.

## api docs

visit `https://futurestudiosaas.docs.buildwithfern.com/future-studio/central/test` (scramble, auto-generated from code). a postman collection is also in `postman/future_studio_saas.postman_collection.json` — import it, run the two "Auth > Login" requests first, everything else picks up the token automatically.
