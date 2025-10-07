<template>
    <div class="flex flex-col gap-6 p-3">
        <!-- Overview cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-lg p-4 bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow">
                <div class="text-sm opacity-80">Total Breeders</div>
                <div class="text-3xl font-bold">{{ loading ? '—' : stat('breeders') }}</div>
            </div>
            <div class="rounded-lg p-4 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow">
                <div class="text-sm opacity-80">Total Commodities</div>
                <div class="text-3xl font-bold">{{ loading ? '—' : stat('commodities') }}</div>
            </div>
            <div class="rounded-lg p-4 bg-gradient-to-br from-fuchsia-500 to-fuchsia-600 text-white shadow">
                <div class="text-sm opacity-80">Regions Covered</div>
                <div class="text-3xl font-bold">{{ loading ? '—' : stat('regions') }}</div>
            </div>
            <div class="rounded-lg p-4 bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow">
                <div class="text-sm opacity-80">Institutes</div>
                <div class="text-3xl font-bold">{{ loading ? '—' : stat('institutes') }}</div>
            </div>
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
</template>

<script setup>
import {ref, computed, onMounted} from 'vue';
import ApiService from '@/Modules/core/infrastructure/ApiService';
import BarGraph from '@/Pages/Projects/BreedersMap/presentation/components/summary/components/BarGraph.vue';
import DoughnutGraph from '@/Pages/Projects/BreedersMap/presentation/components/summary/components/DoughnutGraph.vue';

const loading = ref(true);
const loadingRecent = ref(true);
const overview = ref({totals: {}, charts: {breedersByRegion: [], commoditiesByName: []}});
const recent = ref({breeders: [], commodities: []});
const myStats = ref({isBreeder: false, stats: null});

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

onMounted(async () => {
    try {
        loading.value = true;
        await Promise.all([fetchOverview(), fetchMyStats()]);
    } finally {
        loading.value = false;
    }
    try {
        loadingRecent.value = true;
        await fetchRecent();
    } finally {
        loadingRecent.value = false;
    }
});

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
