# PIN System (CBC BMapDB)

The Crop Biotechnology Center's Plant Breeders and Innovators Network (PIN) System bundles a Laravel 10 backend, a suite of domain-specific modules, and a Vue 3 + Inertia frontend to surface breeder registries, geographic mapping data, and compliance content for CBC partners.

## Highlights
- Multi-module backend: [app/Repository](app/Repository) and the PbMap/TwgdB modules deliver a centralized filter pipeline, repository services, and map-aware data aggregation.
- Vue 3 + Inertia + Tailwind UI: [resources/js/Pages](resources/js/Pages) contains the production UI, including the support/legal experiences under [resources/js/Pages/Support](resources/js/Pages/Support).
- Compliance-ready pages: the privacy policy and terms documents now live in [resources/markdown](resources/markdown), while the full design system lives in [UI_UX_DESIGN_GUIDE.md](UI_UX_DESIGN_GUIDE.md).

## Tech stack
- **Backend:** Laravel 10, Jetstream, Passport, Sanctum, OpenAI PHP integration, Spatie permissions, and a repository/filter pipeline that centers `AbstractRepoService` and `FilterPipeline`.
- **Frontend:** Vue 3 + Inertia loaded through Vite, Ziggy for route helpers, Tailwind CSS with the forms and typography plugins, and Leaflet/Three-powered visualizations maintained in the JavaScript asset pipeline.
- **Build & tooling:** Composer for PHP deps, npm/Vite for assets, Vitest for UI tests, and PHPUnit for PHP coverage.

## Getting started

### Prerequisites
- PHP 8.1+, Composer, and Node.js 18+/npm 10+.
- A relational database (MySQL, MariaDB, or PostgreSQL) plus Redis if you plan to use queues or broadcasting.
- Valid credentials for optional services (OpenAI, SMTP, file storage, etc.).

### Installation
1. Run `composer install` to pull PHP dependencies.
2. Copy `cp .env.example .env` and update values for `APP_URL`, database credentials, mail driver, `OPENAI_API_KEY`, and any mapping API keys.
3. Run `php artisan key:generate` and `php artisan migrate --seed` to prepare the database.
4. Create the symbolic link with `php artisan storage:link` so uploaded files are accessible.
5. Install the JS stack: `npm install` and then `npm run dev` (or `npm run build` for production).

### Environment notes
- `DB_*` values should match your database and the PbMap/TWG dataset seeds.
- `QUEUE_CONNECTION`, `BROADCAST_DRIVER`, and `CACHE_DRIVER` default to sync but can be switched to Redis.
- Optional integrations such as OpenAI, mail, or storage must match the credentials you supply to `.env`.

## Running locally
- Start the backend with `php artisan serve --host=127.0.0.1 --port=8000`.
- Keep front-end assets live with `npm run dev -- --host 0.0.0.0 --port 5173` to leverage Vite's hot module reloading.
- For production audits, run `npm run build` and `php artisan view:cache`, `php artisan route:cache`, `php artisan config:cache`.

## Testing
- `php artisan test` exercises the PHP unit and feature suites.
- `npm run test` (or `npm run test:watch`/`npm run test:ui`) runs the Vitest suite for Vue components.
- `php artisan pint` keeps the PHP style consistent, while `npm run lint` (if configured) should align with frontend expectations.

## Architecture notes
- All repository queries route through [app/Repository/AbstractRepoService](app/Repository/AbstractRepoService.php) and [app/Repository/Filters/FilterPipeline](app/Repository/Filters/FilterPipeline.php) so filters stay consistent.
- Map visualizations and analytics live in [modules/PbMap](modules/PbMap) and [modules/TwgDb](modules/TwgDb), while supporting services such as [app/Services/MapDataFilterService](app/Services/MapDataFilterService.php) encapsulate aggregation logic.
- Support/legal pages use the Inertia layout [resources/js/Pages/Support/components/InfoPageLayout.vue](resources/js/Pages/Support/components/InfoPageLayout.vue) to stay on-brand while rendering markdown-driven policy text.

## Documentation & governance
- Read [docs/AUDIT_REPORT.md](docs/AUDIT_REPORT.md) for the current architecture audit and refactor roadmap.
- Follow the [UI_UX_DESIGN_GUIDE.md](UI_UX_DESIGN_GUIDE.md) rules for every new component, especially typography, colors, and Inertia navigation patterns.
- Legal text is stored in [resources/markdown/policy.md](resources/markdown/policy.md) and [resources/markdown/terms.md](resources/markdown/terms.md); update them whenever data-treatment or usage rules change.

## Support & contributions
- Report issues via GitHub or email cropbiotechcenter@gmail.com with a summary, steps to reproduce, and log snippets.
- Before submitting changes, re-run the PHP and JS test suites, keep migrations reversible, and honor [resources/js/Pages/Support](resources/js/Pages/Support) copy for compliance updates.
- For new modules, prefer suffixing names with `V2` when correcting existing components, and keep new documentation aligned with the design guide.