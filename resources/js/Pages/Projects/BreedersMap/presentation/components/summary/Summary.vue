<template>
    <DashboardShell
        title="BreedersMap Database"
        :isLoading="loading"
        :lastUpdated="lastUpdated"
        @refresh="refreshDashboard"
    >
        <div class="flex flex-col gap-6">
            <!-- Overview cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <dashboard-summary-card
                    title="Total Breeders"
                    to="projects.breedersmap.breeder"
                    background-color="bg-gradient-to-br from-blue-500 to-blue-600"
                    :sum-value="loading ? '—' : stat('breeders')">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 16 16">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M6 6.75v8.5a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2.75a.75.75 0 0 0 1.5 0v-2.5a.25.25 0 0 1 .5 0"/>
                    </svg>
                </dashboard-summary-card>
                <dashboard-summary-card
                    title="Total Commodities"
                    to="projects.breedersmap.commodity"
                    background-color="bg-gradient-to-br from-emerald-500 to-emerald-600"
                    :sum-value="loading ? '—' : stat('commodities')">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 16 16">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/>
                    </svg>
                </dashboard-summary-card>
                <dashboard-summary-card
                    title="Regions Covered"
                    to="projects.breedersmap.geomap"
                    background-color="bg-gradient-to-br from-fuchsia-500 to-fuchsia-600"
                    :sum-value="loading ? '—' : stat('regions')">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 16 16">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="M8 16.016a7.5 7.5 0 0 0 1.962-14.74A1 1 0 0 0 9 0H7a1 1 0 0 0-.962 1.276A7.5 7.5 0 0 0 8 16.016m6.5-7.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="m6.94 7.44 4.95-2.83-2.83 4.95-4.949 2.83 2.828-4.95z"/>
                    </svg>
                </dashboard-summary-card>
                <dashboard-summary-card
                    title="Institutes"
                    to="projects.breedersmap.geomap"
                    background-color="bg-gradient-to-br from-amber-500 to-amber-600"
                    :sum-value="loading ? '—' : stat('institutes')">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 16 16">
                        <path  stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/>
                        <path  stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z"/>
                    </svg>
                </dashboard-summary-card>
                <dashboard-summary-card
                    title="Pending Commodities"
                    to="projects.breedersmap.geomap"
                    background-color="bg-gradient-to-br from-yellow-500 to-yellow-600"
                    :sum-value="loading ? '—' : stat('pendingCommodities')">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 16 16">
                        <path  stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/>
                        <path  stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z"/>
                    </svg>
                </dashboard-summary-card>
            </div>

            <!-- Role-aware: My stats for breeders -->
            <div v-if="!loading && myStats.isBreeder && myStats.stats" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-lg p-4 bg-white shadow border">
                    <div class="text-sm text-gray-500">My Commodities</div>
                    <div class="text-2xl font-semibold text-gray-800">{{ myStats.stats.myCommodities }}</div>
                </div>
                <div class="rounded-lg p-4 bg-white shadow border">
                    <div class="text-sm text-gray-500">Distinct Varieties</div>
                    <div class="text-2xl font-semibold text-gray-800">{{ myStats.stats.distinctVarieties }}</div>
                </div>
                <div class="rounded-lg p-4 bg-white shadow border">
                    <div class="text-sm text-gray-500">With Population Data</div>
                    <div class="text-2xl font-semibold text-gray-800">{{ myStats.stats.withPopulation }}</div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 rounded-lg bg-white shadow p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-gray-800 font-semibold">Breeders by Region</h3>
                        <span v-if="loading" class="text-sm text-gray-400">Loading…</span>
                    </div>
                    <div class="min-h-[260px]">
                        <BarGraph v-if="!loading" :data="breedersByRegionChart"/>
                        <div v-else class="w-full h-[260px] animate-pulse bg-gray-100 rounded"/>
                    </div>
                </div>
                <div class="rounded-lg bg-white shadow p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-gray-800 font-semibold">Commodities</h3>
                        <span v-if="loading" class="text-sm text-gray-400">Loading…</span>
                    </div>
                    <div class="min-h-[260px]">
                        <DoughnutGraph v-if="!loading" :data="commoditiesByNameChart"/>
                        <div v-else class="w-full h-[260px] animate-pulse bg-gray-100 rounded"/>
                    </div>
                </div>
            </div>

            <!-- Recent activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-lg bg-white shadow p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-gray-800 font-semibold">Recent Breeders</h3>
                        <span v-if="loadingRecent" class="text-sm text-gray-400">Loading…</span>
                    </div>
                    <div class="divide-y">
                        <div v-if="!loadingRecent && (!recent.breeders || !recent.breeders.length)"
                             class="text-sm text-gray-500 py-6 text-center">No recent breeders
                        </div>
                        <div v-else v-for="b in recent.breeders" :key="b.id" class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">{{ b.name }}</div>
                                <div class="text-xs text-gray-500">{{ b.institute || '—' }} <span
                                    v-if="b.region">• {{ b.region }}</span></div>
                            </div>
                            <div class="text-xs text-gray-400">{{ new Date(b.created_at).toLocaleDateString() }}</div>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white shadow p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-gray-800 font-semibold">Recent Commodities</h3>
                        <span v-if="loadingRecent" class="text-sm text-gray-400">Loading…</span>
                    </div>
                    <div class="divide-y">
                        <div v-if="!loadingRecent && (!recent.commodities || !recent.commodities.length)"
                             class="text-sm text-gray-500 py-6 text-center">No recent commodities
                        </div>
                        <div v-else v-for="c in recent.commodities" :key="c.id"
                             class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">{{ c.name }}<span v-if="c.variety"
                                                                                         class="text-gray-500"> — {{
                                        c.variety
                                    }}</span></div>
                                <div class="text-xs text-gray-500">{{ c.breeder || '—' }} <span
                                    v-if="c.institute">• {{ c.institute }}</span> <span v-if="c.region">• {{
                                        c.region
                                    }}</span></div>
                            </div>
                            <div class="text-xs text-gray-400">{{ new Date(c.created_at).toLocaleDateString() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardShell>
</template>

<script setup>
import {ref, computed, onMounted} from 'vue';
import ApiService from '@/Modules/core/infrastructure/ApiService';
import BarGraph from '@/Pages/Projects/BreedersMap/presentation/components/summary/components/BarGraph.vue';
import DoughnutGraph from '@/Pages/Projects/BreedersMap/presentation/components/summary/components/DoughnutGraph.vue';
import DashboardShell from '@/Pages/Dashboard/components/DashboardShell.vue';
import DashboardSummaryCard from "@/Pages/Dashboard/components/DashboardSummaryCard.vue";

const loading = ref(true);
const loadingRecent = ref(true);
const overview = ref({totals: {}, charts: {breedersByRegion: [], commoditiesByName: []}});
const recent = ref({breeders: [], commodities: []});
const myStats = ref({isBreeder: false, stats: null});
const lastUpdated = ref(null);

const fetchOverview = async () => {
    const svc = new ApiService('/api/breeders-dashboard/overview');
    const res = await svc.get();
    overview.value = res?.data || {totals: {}, charts: {breedersByRegion: [], commoditiesByName: []}};
};

const fetchRecent = async () => {
    const svc = new ApiService('/api/breeders-dashboard/recent');
    const res = await svc.get();
    recent.value = res?.data || {breeders: [], commodities: []};
};

const fetchMyStats = async () => {
    const svc = new ApiService('/api/breeders-dashboard/my-stats');
    const res = await svc.get();
    myStats.value = res?.data || {isBreeder: false, stats: null};
};

const refreshDashboard = async () => {
    try {
        loading.value = true;
        await Promise.all([fetchOverview(), fetchMyStats()]);
        lastUpdated.value = new Date().toISOString();
    } finally {
        loading.value = false;
    }
    try {
        loadingRecent.value = true;
        await fetchRecent();
    } finally {
        loadingRecent.value = false;
    }
};

onMounted(refreshDashboard);

const stat = (key, def = 0) => overview.value?.totals?.[key] ?? def;

const breedersByRegionChart = computed(() => {
    const items = overview.value?.charts?.breedersByRegion || [];
    return {
        labels: items.map(i => i.label),
        datasets: [{
            label: 'Breeders by Region',
            data: items.map(i => i.total),
            backgroundColor: ['#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#1d4ed8', '#2563eb', '#1e40af', '#38bdf8', '#06b6d4', '#34d399', '#fbbf24', '#fb7185'],
            borderWidth: 1
        }]
    };
});

const commoditiesByNameChart = computed(() => {
    const items = overview.value?.charts?.commoditiesByName || [];
    return {
        labels: items.map(i => i.label),
        datasets: [{
            data: items.map(i => i.total),
            backgroundColor: ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#059669', '#047857', '#064e3b', '#f59e0b', '#fbbf24', '#fde68a', '#ef4444', '#fca5a5'],
            borderWidth: 1
        }]
    };
});
</script>
