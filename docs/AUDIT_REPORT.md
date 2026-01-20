# System Architecture Audit Report

Date: 2026-01-20

Scope
- Full codebase inspection for alignment with target architecture.
- Backend target: repository-based modeling with filter pipeline.
- Frontend target: DDD layering (domain, dto, infrastructure, presentation).
- Generic goals: simple, optimized, standardized, maintainable.

Target Architecture (Architecture-First)
1) Backend Repository + Pipeline
- `AbstractRepoService` provides a standardized `search()` using `FilterPipeline`.
- Filters should be parameter-driven and applied by the pipeline in a consistent order.
- Controllers and Actions should delegate to repositories instead of building manual queries.

2) Frontend DDD
- Domain: pure business objects, no transport or framework dependencies.
- DTO: mapping layer for API payloads.
- Infrastructure: API services, transport, persistence.
- Presentation: UI components and pages only.

Current State Summary
- Repository pipeline exists and is mature in [app/Repository/AbstractRepoService.php](app/Repository/AbstractRepoService.php) and [app/Repository/Filters/FilterPipeline.php](app/Repository/Filters/FilterPipeline.php).
- PbMap module still uses manual filter methods (non-pipeline) in [modules/PbMap/Repositories/CommodityRepo.php](modules/PbMap/Repositories/CommodityRepo.php) and [modules/PbMap/Repositories/BreederRepo.php](modules/PbMap/Repositories/BreederRepo.php).
- Map data uses a separate Strategy pattern in [app/Services/MapDataFilterService.php](app/Services/MapDataFilterService.php) and [app/Services/Filters](app/Services/Filters).
- Frontend DDD folders exist in [resources/js/Pages/Projects](resources/js/Pages/Projects) with domain/dto/presentation/infrastructure.
- Domain classes in modules often include API routes and transport details.

Findings and Required Actions

Backend: Repository + Pipeline Alignment
1) Multiple filtering systems are active (pipeline, repo-specific `applyFilters`, strategy service).
   - Evidence:
     - [app/Repository/AbstractRepoService.php](app/Repository/AbstractRepoService.php)
     - [modules/PbMap/Repositories/CommodityRepo.php](modules/PbMap/Repositories/CommodityRepo.php)
     - [modules/PbMap/Repositories/BreederRepo.php](modules/PbMap/Repositories/BreederRepo.php)
     - [app/Services/MapDataFilterService.php](app/Services/MapDataFilterService.php)
   - Action:
     - Standardize on `FilterPipeline` for all repository queries.
     - Decide if map aggregation stays as a separate service; if yes, document it as an exception and align its inputs to the same parameter schema.

2) Actions/controllers bypass the pipeline and build queries directly.
   - Evidence:
     - [modules/PbMap/Actions/GenerateBreederSummaryAction.php](modules/PbMap/Actions/GenerateBreederSummaryAction.php)
   - Action:
     - Move filtering into repository `search()` calls with pipeline parameters.
     - Keep actions as orchestration only (no joins or select raw logic).

3) Legacy filter helpers still referenced, diverging from pipeline.
   - Evidence:
     - [modules/PbMap/Actions/GenerateBreederSummaryAction.php](modules/PbMap/Actions/GenerateBreederSummaryAction.php) uses `applyGeoFilters`, `applySearchFilters`, `applySorting`.
   - Action:
     - Replace helper usage with pipeline parameters in `search()`.
     - Deprecate helper methods once parity is achieved.

4) Module-specific filter DTOs drift from the parameter schema.
   - Evidence:
     - [modules/PbMap/Filters/CommodityFilter.php](modules/PbMap/Filters/CommodityFilter.php)
   - Action:
     - Replace DTO-driven filtering with `Collection` parameters that map 1:1 to pipeline filters.

5) Custom filtering trait overlaps with pipeline behavior.
   - Evidence:
     - [app/Traits/VisualizeData.php](app/Traits/VisualizeData.php)
   - Action:
     - Consolidate filter logic into pipeline filters or document this trait as a visualization-specific exception.

Frontend: DDD Alignment
6) Domain layer depends on transport and routing details.
   - Evidence:
     - [resources/js/Pages/Projects/BreedersMap/domain/Commodity.ts](resources/js/Pages/Projects/BreedersMap/domain/Commodity.ts) uses axios and route config.
     - [resources/js/Pages/Projects/TWG/domain/Project.ts](resources/js/Pages/Projects/TWG/domain/Project.ts) embeds API URIs and request concerns.
   - Action:
     - Move API endpoints and transport to infrastructure services.
     - Keep domain classes pure and API-agnostic.

7) Infrastructure code is nested under presentation.
   - Evidence:
     - [resources/js/Pages/Projects/BreedersMap/presentation/components/map/infrastructure/MapApiService.js](resources/js/Pages/Projects/BreedersMap/presentation/components/map/infrastructure/MapApiService.js)
   - Action:
     - Relocate infrastructure services to the module’s infrastructure layer.
     - Keep presentation folders UI-only.

8) Inconsistent module boundaries and pathing reduce standardization.
   - Evidence:
     - Mix of module-local and global core imports in [resources/js/Pages/Projects](resources/js/Pages/Projects) and [resources/js/Modules/core](resources/js/Modules/core).
   - Action:
     - Define a single DDD import boundary rule (e.g., domain can import core domain only; infrastructure can import API services only).
     - Enforce path conventions for domain/dto/infrastructure/presentation.

Documentation Consolidation
9) Documentation is fragmented across multiple dashboard and repository files.
   - Action:
     - This report replaces previous docs and serves as the single unified document.

Standardization Checklist (Target State)
- Backend
  - All queries use `AbstractRepoService::search()` with pipeline parameters.
  - No direct joins or `selectRaw` in actions/controllers except specialized report endpoints.
  - One source of truth for filter names and behavior.

- Frontend
  - Domain classes contain only behavior/state.
  - Infrastructure services handle HTTP and routing.
  - Presentation does not access infrastructure directly.

Refactor Roadmap (Non-Implementation)
Phase 1: Standardize backend filter entry points
- Replace module-specific `applyFilters` usage with pipeline parameters.
- Add missing pipeline filters if parity gaps exist.

Phase 2: Align map data strategy with repository pipeline
- Either refactor strategies into repository filters or document them as a specialized aggregation service.

Phase 3: DDD cleanup on frontend
- Extract transport concerns from domain classes into infrastructure services.
- Move infrastructure out of presentation paths.

Appendix: Key Files
- Backend repository pipeline:
  - [app/Repository/AbstractRepoService.php](app/Repository/AbstractRepoService.php)
  - [app/Repository/Filters/FilterPipeline.php](app/Repository/Filters/FilterPipeline.php)
- PbMap repositories and actions:
  - [modules/PbMap/Repositories/CommodityRepo.php](modules/PbMap/Repositories/CommodityRepo.php)
  - [modules/PbMap/Repositories/BreederRepo.php](modules/PbMap/Repositories/BreederRepo.php)
  - [modules/PbMap/Actions/GenerateBreederSummaryAction.php](modules/PbMap/Actions/GenerateBreederSummaryAction.php)
- Map strategy filtering:
  - [app/Services/MapDataFilterService.php](app/Services/MapDataFilterService.php)
  - [app/Services/Filters](app/Services/Filters)
- Frontend DDD modules:
  - [resources/js/Pages/Projects](resources/js/Pages/Projects)
  - [resources/js/Modules/core](resources/js/Modules/core)