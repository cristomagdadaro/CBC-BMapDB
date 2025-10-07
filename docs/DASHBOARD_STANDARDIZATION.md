# Dashboard Standardization (2025-10-07)

Goal: unify the look-and-feel and the data fetching pattern of dashboards using the design approach of `TWGSummary.vue`.

What’s included
- Shared shell UI: `resources/js/Components/DashboardShell.vue`
  - Props: `title: string`, `isLoading?: boolean`, `lastUpdated?: string|null`
  - Emits: `refresh`
  - Slots: default (content), `actions` (for extra header buttons; e.g., export)
- Fetching helper: `resources/js/composables/useDashboard.ts`
  - Provides `data`, `isLoading`, `lastUpdated`, `error`, `refresh()` for a passed async fetcher
- Refactors
  - TWG: `TWGSummary.vue` now uses `DashboardShell` and wires Export buttons via `useExport`
  - BreedersMap: `Summary.vue` now uses `DashboardShell`, has a unified `refreshDashboard()` and `lastUpdated`

How to use DashboardShell
1) Import and wrap your dashboard content

   ```vue
   <DashboardShell
     title="My Dashboard"
     :isLoading="isLoading"
     :lastUpdated="lastUpdated"
     @refresh="refreshDashboard"
   >
     <template #actions>
       <!-- optional buttons like Export -->
     </template>

     <!-- your content -->
   </DashboardShell>
   ```

2) Keep your data logic as-is, but ensure you update `isLoading` and `lastUpdated` inside your refresh.

Using useDashboard (optional)
- For simple dashboards with a single fetch, you can use the composable:

  ```ts
  const { data, isLoading, lastUpdated, refresh } = useDashboard(async () => {
    const svc = new ApiService('/api/your-endpoint');
    const res = await svc.get();
    return res.data; // or massage into a shape you prefer
  });
  ```

  Then pass `isLoading`/`lastUpdated` to `DashboardShell` and call `refresh()` on `@refresh`.

Notes
- Charts: destroy existing chart instances before re-creating to avoid memory leaks.
- Exports: use the existing `useExport` + `ExportService` for Excel/PDF in your `#actions` slot.

Migration checklist for a dashboard
- [ ] Wrap UI in `DashboardShell` and pass `title`, `isLoading`, `lastUpdated`
- [ ] Implement a single `refreshDashboard()` that fetches and updates `lastUpdated`
- [ ] (Optional) Move to `useDashboard` if your data comes from a single fetch
- [ ] (Optional) Add export actions with `useExport`

Troubleshooting
- If you see SVG attribute warnings in IDE, they’re benign and do not affect functionality.
- Ensure `@` alias resolves to `resources/js` (default Vite config already does in this project).

