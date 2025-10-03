# Running Locally

Start the Laravel app and Vite dev server:

./vendor/bin/sail up -d
./vendor/bin/sail npm run dev

---

# Decisions & Limitations

- Auth: Uses Laravel Breeze. Minimal role handling (`users.role`).
- DB: MySQL in Docker for local.
- Soft delete: No restore/force-delete UI yet.
- No uploads: Only form data stored.
- Emails: Breeze stubs exist, but no mailer is configured for local.
- Production: Demo-ready but not hardened (no rate limiting, no logging/audit, no queue workers).

---

# Deploy Flow

The goal is to build once, run anywhere with consistent releases across all environments (Dev → Test → Staging → Production).

## Build & Package (CI)

- Code is pushed to GitHub (or other VCS).
- GitHub Actions runs:
  - PHP/Laravel tests (`artisan test`)
  - Frontend build (`npm run build`)
  - Composer install (no dev dependencies for prod)
- A Docker image is built that includes:
  - Laravel app
  - Compiled frontend assets
  - PHP runtime + required extensions
- This image is tagged with the commit SHA or release version.

## Artifact Storage (Registry)

- The Docker image is pushed to a container registry (e.g., AWS ECR, Azure ACR, or GitHub Container Registry).
- The registry serves as the source of truth for deployable versions.
- Each image is immutable and can be rolled back by redeploying the previous tag.

## Deployment (CD)

- Octopus Deploy orchestrates deployments across environments.
- The deploy process:
  1. Pulls the tagged Docker image from the registry.
  2. Spins up containers in the target environment (ECS, AKS, Kubernetes, or VM host).
  3. Runs database migrations (`artisan migrate --force`).
  4. Performs health checks to confirm the app is running.

- Environment-specific configuration (e.g., DB credentials, app key, API tokens) are injected at deploy time via Octopus Variables or a secrets provider (Vault/Key Vault).
