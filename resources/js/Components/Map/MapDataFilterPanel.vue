<script setup>
import { ref, computed, watch, onMounted } from "vue";
import SelectField from "@/Components/Form/SelectField.vue";
import TextField from "@/Components/Form/TextField.vue";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.ts";
import BaseResponse from "@/Modules/core/domain/base/BaseResponse";

const props = defineProps({
    initialDataType: {
        type: String,
        default: "breeders",
    },
    initialFilters: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["dataUpdated", "filtersChanged", "close"]);

// API Services
const mapDataApi = ref(null);
const filterOptionsApi = ref(null);
const summaryApi = ref(null);

// Reactive state
const filters = ref({
    data_type: props.initialDataType,
    filter_by: null,
    ...props.initialFilters,
});
const filterOptions = ref({});
const mapData = ref([]);
const summaryData = ref({});
const error = ref(null);

// Data type options
const dataTypeOptions = [
    { value: "commodities", label: "Commodities" },
    { value: "breeders", label: "Breeders" },
    { value: "institutes", label: "Institutions" },
];

// Filter by options
const filterByOptions = [
    { value: "region", label: "By Region" },
    { value: "province", label: "By Province" },
    { value: "city", label: "By City" },
    { value: "institute", label: "By Institute" },
];

// Computed properties
const currentDataType = computed(() => filters.value.data_type);

const availableOptions = computed(() => {
    const options = { ...filterOptions.value };
    delete options.group_by_options;

    // Filter based on filter_by selection
    const filteredOptions = {};
    const currentFilterBy = filters.value.filter_by;

    Object.entries(options).forEach(([key, value]) => {
        // Show the filter only if it matches the filter_by or is a basic filter
        if (shouldShowFilter(key, currentFilterBy)) {
            filteredOptions[key] = value;
        }
    });

    return filteredOptions;
});

// Helper function to determine which filters to show based on filter_by
const shouldShowFilter = (filterKey, filterBy) => {
    // If no filter_by is selected, show all relevant filters
    if (!filterBy) {
        // Always show basic geographic filters for hierarchical filtering
        if (["region", "province", "city"].includes(filterKey)) {
            return true;
        }

        // Show commodity filter when data type is commodities
        if (filterKey === "commodities") {
            return currentDataType.value === "commodities";
        }

        // Show institute filter
        if (filterKey === "institutes") {
            return (
                currentDataType.value === "institutes" ||
                currentDataType.value === "breeders"
            );
        }

        // Show breeder types filter when relevant
        if (filterKey === "breeder_types") {
            return currentDataType.value === "breeders";
        }

        // Show institute types filter when relevant
        if (filterKey === "institute_types") {
            return currentDataType.value === "institutes";
        }

        return true;
    }

    // Show filters based on filter_by selection
    if (filterBy === "commodity" && filterKey === "commodities") {
        return true;
    }
    if (filterBy === "region" && filterKey === "regions") {
        return true;
    }
    if (filterBy === "province" && filterKey === "provinces") {
        return true;
    }
    if (filterBy === "city" && filterKey === "cities") {
        return true;
    }

    // Always show other data-specific filters
    if (filterKey === "breeder_types" && currentDataType.value === "breeders") {
        return true;
    }
    if (
        filterKey === "institute_types" &&
        currentDataType.value === "institutes"
    ) {
        return true;
    }
    if (filterKey === "institutes") {
        return true;
    }

    return false;
};

const loading = computed(() => {
    return (
        mapDataApi.value?.processing ||
        filterOptionsApi.value?.processing ||
        summaryApi.value?.processing
    );
});

// Initialize API services
const initializeApiServices = () => {
    mapDataApi.value = new ApiService("/api/map-data");
    filterOptionsApi.value = new ApiService("/api/map-data/filter-options");
    summaryApi.value = new ApiService("/api/map-data/summary");
};

// API methods using ApiService
const fetchFilterOptions = async (dataType) => {
    try {
        error.value = null;
        const params = { data_type: dataType };
        const response = await filterOptionsApi.value.get(params);
        if (response.status === 200 && response.data.success) {
            filterOptions.value = response?.data?.options || {};
        } else {
            throw new Error(
                response.message || "Failed to fetch filter options",
            );
        }
    } catch (err) {
        error.value = err.message || "Failed to fetch filter options";
        console.error("Failed to fetch filter options:", err);
    }
};

const fetchMapData = async () => {
    try {
        error.value = null;

        // Clean filters - remove empty values
        const cleanFilters = {};
        Object.entries(filters.value).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== "") {
                cleanFilters[key] = value;
            }
        });

        const response = await mapDataApi.value.get(cleanFilters);

        if (response.status === 200 && response.data.success) {
            mapData.value = response.data.data || [];

            // Update filter options if they're included in response
            if (response.data.filter_options) {
                filterOptions.value = response.data.filter_options;
            }

            emit("dataUpdated", {
                data: response.data.data || [],
                metadata: response.data.metadata || {},
                filters: filters.value,
            });
        } else {
            throw new Error(response.message || "Failed to fetch map data");
        }
    } catch (err) {
        error.value = err.message || "Failed to fetch map data";
        console.error("Failed to fetch map data:", err);
    }
};

const fetchSummaryData = async () => {
    try {
        // Clean filters - remove empty values
        const cleanFilters = {};
        Object.entries(filters.value).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== "") {
                cleanFilters[key] = value;
            }
        });

        const response = await summaryApi.value.get(cleanFilters);

        if (response instanceof BaseResponse && response.success) {
            summaryData.value = response.data.summary || {};
        }
    } catch (err) {
        console.error("Failed to fetch summary data:", err);
        // Don't show error for summary data as it's not critical
    }
};

// Filter management
const updateFilter = (key, value) => {
    filters.value[key] = value;
    emit("filtersChanged", filters.value);
};

const clearFilters = () => {
    const dataType = filters.value.data_type;

    filters.value = {
        data_type: dataType,
    };

    emit("filtersChanged", filters.value);
};

const resetToDefaults = () => {
    filters.value = {
        data_type: "commodities",
    };
    emit("filtersChanged", filters.value);
};

// Watchers
watch(
    () => filters.value.data_type,
    async (newDataType) => {
        if (newDataType) {
            // Reset other filters when data type changes
            filters.value = {
                data_type: newDataType,
            };

            await fetchFilterOptions(newDataType);
            await fetchMapData();
            await fetchSummaryData();
        }
    },
    { immediate: false },
);

watch(
    filters,
    async () => {
        await fetchMapData();
        await fetchSummaryData();
    },
    { deep: true, immediate: false },
);

// Lifecycle
onMounted(async () => {
    initializeApiServices();
    await fetchFilterOptions(currentDataType.value);
    await fetchMapData();
    await fetchSummaryData();
});

// Expose methods for parent components
defineExpose({
    refreshData: async () => {
        await fetchMapData();
        await fetchSummaryData();
    },
    getFilters: () => filters.value,
    setFilters: (newFilters) => {
        filters.value = { ...filters.value, ...newFilters };
    },
});
</script>

<template>
    <div class="map-data-filter-panel p-4 sm:p-5 overflow-visible relative text-gray-800 dark:text-gray-100">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100">Data Filters</h3>
            </div>
            <div class="flex items-center gap-1.5">
                <button
                    @click="clearFilters"
                    class="text-xs text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-2 py-1 rounded border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors"
                    title="Clear current filters"
                >
                    Clear
                </button>
                <button
                    @click="resetToDefaults"
                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 px-2 py-1 rounded border border-indigo-200 dark:border-indigo-900/60 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition-colors"
                    title="Reset to default view"
                >
                    Reset
                </button>
                <button
                    @click="emit('close')"
                    class="lg:hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-md"
                    title="Close filters"
                >
                    <CloseIcon class="w-4 h-4" />
                </button>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div
            v-if="loading"
            class="absolute inset-0 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xs flex items-center justify-center rounded-xl z-20"
        >
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <LoaderIcon class="w-5 h-5 animate-spin text-emerald-600" />
                <span>Loading...</span>
            </div>
        </div>

        <!-- Error Message -->
        <div
            v-if="error"
            class="mb-4 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/60 rounded-lg"
        >
            <div class="flex">
                <div class="text-xs text-red-700 dark:text-red-300">
                    {{ error }}
                </div>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="space-y-3.5 overflow-visible">
            <!-- Data Type Selection -->
            <SelectField
                label="Data List"
                v-model="filters.data_type"
                :options="dataTypeOptions"
                :disabled="loading"
                :searchable="false"
                :clearable="false"
                @change="updateFilter('data_type', $event?.value)"
            />

            <!-- Filter By Selection -->
            <SelectField
                label="Filter By"
                v-model="filters.filter_by"
                :options="filterByOptions"
                :disabled="loading"
                :searchable="false"
                :clearable="true"
                @change="updateFilter('filter_by', $event?.value)"
            />

            <!-- Dynamic Filter Options -->
            <template
                v-for="(options, filterKey) in availableOptions"
                :key="filterKey"
            >
                <SelectField
                    v-if="options.length > 0"
                    :label="
                        filterKey
                            .replace('_', ' ')
                            .replace(/\b\w/g, (l) => l.toUpperCase())
                    "
                    v-model="filters[filterKey]"
                    :options="options"
                    :disabled="loading"
                    placeholder="All"
                    @change="updateFilter(filterKey, $event?.value)"
                />
            </template>

            <!-- Search Field -->
            <TextField
                v-if="currentDataType === 'breeders'"
                label="Search"
                v-model="filters.search"
                :disabled="loading"
                placeholder="Type to search..."
                :debounce="500"
                @input="updateFilter('search', $event)"
            />
        </div>

        <!-- Summary Statistics -->
        <div
            v-if="Object.keys(summaryData).length > 0"
            class="mt-4 pt-3.5 border-t border-gray-100 dark:border-slate-800"
        >
            <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2.5">
                Summary
            </h4>
            <div class="grid grid-cols-2 gap-2">
                <div
                    v-for="(value, key) in summaryData"
                    :key="key"
                    class="text-center p-2.5 bg-gray-50/80 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-700/50 rounded-lg"
                >
                    <div class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100">
                        {{ value }}
                    </div>
                    <div class="text-[0.65rem] sm:text-xs text-gray-500 dark:text-gray-400 capitalize">
                        {{ key.replace(/total_|_/g, " ").trim() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.map-data-filter-panel {
    position: relative;
}
</style>
