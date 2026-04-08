# Copilot Instructions for CBC BMapDB

## Project overview

- This repository is a Laravel 10 + PHP 8.1 application with a Vue 3 + Inertia frontend, built with Vite and styled with Tailwind CSS.
- Main product domains:
  - `modules/PbMap` + `resources/js/Pages/Projects/BreedersMap`
  - `modules/TwgDb` + `resources/js/Pages/Projects/TWG`
- Shared frontend primitives live in `resources/js/Modules/core`.
- The `@` alias maps to `resources/js`.

## Backend architecture

- Prefer this flow for CRUD and query work: route -> `FormRequest` -> controller -> repository/action -> model/policy.
- Reuse `App\Http\Controllers\BaseController` and `App\Repository\AbstractRepoService` for standard CRUD behavior.
- For new searchable/filterable queries, extend `App\Repository\Filters\FilterPipeline` instead of creating another filtering system.
- `App\Services\MapDataFilterService` is a deliberate exception for map aggregation endpoints. Reuse it for map-data behavior instead of duplicating aggregation logic elsewhere.
- Keep controllers thin. Put orchestration in actions and persistence/query logic in repositories.
- Preserve authorization via policies and route middleware such as `admin`, `check.status.breedersmap`, and `check.status.twg`.
- Match existing request validation patterns. Read/query requests usually merge the `paginate_parameters`, `filtering_parameters`, and `appendable_parameters` groups from `config/system_variables.php`.
- Keep repository-backed API responses aligned with `config/responses.php`.

## Frontend architecture

- Inertia boots from `resources/js/app.js`. Server-side `Inertia::render()` paths must continue to match the page paths under `resources/js/Pages`.
- Prefer Ziggy route names and the shared `resources/js/Modules/core/infrastructure/ApiService.ts` for API work.
- Project modules are organized around `domain/`, `dto/`, `infrastructure/`, `interface/`, and `presentation/`.
- Keep new domain classes transport-free. Put route names, URLs, and HTTP concerns in `*Endpoints.ts` or infrastructure services.
- Many admin and project models expose static helpers such as `createForm()`, `updateForm()`, `getColumns()`, and `getCardColumns()`. Preserve that pattern when extending forms and data tables.
- There is a `resources/js/router.js`, but Laravel routes plus Inertia page resolution are the primary navigation source of truth. Do not add new client-side routing unless the feature truly needs it.

## UI and content rules

- Follow `UI_UX_DESIGN_GUIDE.md` for new UI work. Reuse design tokens from `tailwind.config.js` and shared utilities from `resources/css/app.css`.
- Support and legal pages use `resources/js/Pages/Support/components/InfoPageLayout.vue`.
- Legal/support copy lives in `resources/markdown/policy.md` and `resources/markdown/terms.md`. Update markdown content instead of hardcoding long policy text in Vue components.

## Testing and validation

- Backend tests are primarily feature tests under `tests/Feature/BreedersMap`, `tests/Feature/MapData`, and `tests/Feature/TWG`.
- Frontend tests use Vitest via the `test` block in `vite.config.js`.
- The PHPUnit setup expects MySQL from `phpunit.xml` and shared bootstrap logic in `tests/TestCase.php`. Do not assume SQLite.
- Useful verification commands: `php artisan test`, `npm run test`, `npm run build`, `php artisan pint`.
- Do not assume an `npm run lint` script exists unless you add it explicitly.

## Repository-specific guardrails

- Follow the target architecture in `docs/AUDIT_REPORT.md` even when older files still show legacy patterns.
- Do not put new infrastructure code under `presentation/` folders.
- Do not move query logic into controllers when a repository or action already exists.
- Avoid bulk edits to large data assets such as the Breeders Map geojson files unless the task is specifically about map geometry/data.
- Preserve existing naming conventions: `*Request`, `*Controller`, `*Repo`, `*Policy`, `*Endpoints`, `*ApiService`, and `presentation/*`.
