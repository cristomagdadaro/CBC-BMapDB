<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import SelectField from '@/Components/Form/SelectField.vue'
import TextField from '@/Components/Form/TextField.vue'
import LoaderIcon from '@/Components/Icons/LoaderIcon.vue'
import ApiService from '@/Modules/core/infrastructure/ApiService.ts'
import BaseResponse from '@/Modules/core/domain/base/BaseResponse'

const props = defineProps({
    initialDataType: {
        type: String,
        default: 'commodities'
    },
    initialFilters: {
        type: Object,
        default: () => ({})
    }
})

const emit = defineEmits(['dataUpdated', 'filtersChanged'])

// API Services
const mapDataApi = ref(null)
const filterOptionsApi = ref(null)
const summaryApi = ref(null)

// Reactive state
const filters = ref({
    data_type: props.initialDataType,
    group_by: 'region',
    ...props.initialFilters
})
const filterOptions = ref({})
const mapData = ref([])
const summaryData = ref({})
const error = ref(null)

// Data type options
const dataTypeOptions = [
    { value: 'commodities', label: 'Commodities' },
    { value: 'breeders', label: 'Breeders' },
    { value: 'institutes', label: 'Institutes' }
]

// Computed properties
const currentDataType = computed(() => filters.value.data_type)

const groupByOptions = computed(() => {
    return filterOptions.value.group_by_options || []
})

const availableOptions = computed(() => {
    const options = { ...filterOptions.value }
    delete options.group_by_options
    return options
})

const loading = computed(() => {
    return mapDataApi.value?.processing ||
           filterOptionsApi.value?.processing ||
           summaryApi.value?.processing
})

// Initialize API services
const initializeApiServices = () => {
    mapDataApi.value = new ApiService('/api/map-data')
    filterOptionsApi.value = new ApiService('/api/map-data/filter-options')
    summaryApi.value = new ApiService('/api/map-data/summary')
}

// API methods using ApiService
const fetchFilterOptions = async (dataType) => {
    try {
        error.value = null
        const params = { data_type: dataType }
        const response = await filterOptionsApi.value.get(params)
        if (response.status === 200 && response.data.success) {
            filterOptions.value = response?.data?.options || {}
        } else {
            throw new Error(response.message || 'Failed to fetch filter options')
        }
    } catch (err) {
        error.value = err.message || 'Failed to fetch filter options'
        console.error('Failed to fetch filter options:', err)
    }
}

const fetchMapData = async () => {
    try {
        error.value = null

        // Clean filters - remove empty values
        const cleanFilters = {}
        Object.entries(filters.value).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== '') {
                cleanFilters[key] = value
            }
        })

        const response = await mapDataApi.value.get(cleanFilters)

        if (response.status === 200 && response.data.success) {
            mapData.value = response.data.data || []

            // Update filter options if they're included in response
            if (response.data.filter_options) {
                filterOptions.value = response.data.filter_options
            }

            emit('dataUpdated', {
                data: response.data.data || [],
                metadata: response.data.metadata || {},
                filters: filters.value
            })
        } else {
            throw new Error(response.message || 'Failed to fetch map data')
        }
    } catch (err) {
        error.value = err.message || 'Failed to fetch map data'
        console.error('Failed to fetch map data:', err)
    }
}

const fetchSummaryData = async () => {
    try {
        // Clean filters - remove empty values
        const cleanFilters = {}
        Object.entries(filters.value).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== '') {
                cleanFilters[key] = value
            }
        })

        const response = await summaryApi.value.get(cleanFilters)

        if (response instanceof BaseResponse && response.success) {
            summaryData.value = response.data.summary || {}
        }
    } catch (err) {
        console.error('Failed to fetch summary data:', err)
        // Don't show error for summary data as it's not critical
    }
}

// Filter management
const updateFilter = (key, value) => {
    filters.value[key] = value
    emit('filtersChanged', filters.value)
}

const clearFilters = () => {
    const dataType = filters.value.data_type
    const groupBy = filters.value.group_by

    filters.value = {
        data_type: dataType,
        group_by: groupBy
    }

    emit('filtersChanged', filters.value)
}

const resetToDefaults = () => {
    filters.value = {
        data_type: 'commodities',
        group_by: 'region'
    }
    emit('filtersChanged', filters.value)
}

// Watchers
watch(() => filters.value.data_type, async (newDataType) => {
    if (newDataType) {
        // Reset other filters when data type changes
        filters.value = {
            data_type: newDataType,
            group_by: 'region'
        }

        await fetchFilterOptions(newDataType)
        await fetchMapData()
        await fetchSummaryData()
    }
}, { immediate: false })

watch(filters, async () => {
    await fetchMapData()
    await fetchSummaryData()
}, { deep: true, immediate: false })

// Lifecycle
onMounted(async () => {
    initializeApiServices()
    await fetchFilterOptions(currentDataType.value)
    await fetchMapData()
    await fetchSummaryData()
})

// Expose methods for parent components
defineExpose({
    refreshData: async () => {
        await fetchMapData()
        await fetchSummaryData()
    },
    getFilters: () => filters.value,
    setFilters: (newFilters) => {
        filters.value = { ...filters.value, ...newFilters }
    }
})
</script>

<template>
    <div class="map-data-filter-panel bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Data Filters</h3>
            <div class="flex items-center gap-2">
                <button
                    @click="clearFilters"
                    class="text-sm text-gray-600 hover:text-gray-800 px-3 py-1 rounded border border-gray-300 hover:border-gray-400 transition-colors"
                >
                    Clear Filters
                </button>
                <button
                    @click="resetToDefaults"
                    class="text-sm text-indigo-600 hover:text-indigo-800 px-3 py-1 rounded border border-indigo-300 hover:border-indigo-400 transition-colors"
                >
                    Reset
                </button>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div v-if="loading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg">
            <div class="flex items-center gap-2 text-gray-600">
                <LoaderIcon class="w-5 h-5 animate-spin" />
                <span>Loading...</span>
            </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
            <div class="flex">
                <div class="text-sm text-red-700">
                    {{ error }}
                </div>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="space-y-4">
            <!-- Data Type Selection -->
            <SelectField
                label="Data Type"
                v-model="filters.data_type"
                :options="dataTypeOptions"
                :disabled="loading"
                :searchable="false"
                :clearable="false"
                @change="updateFilter('data_type', $event?.value)"
            />

            <!-- Group By Selection -->
            <SelectField
                label="Group By"
                v-model="filters.group_by"
                :options="groupByOptions"
                :disabled="loading"
                :searchable="false"
                :clearable="false"
                @change="updateFilter('group_by', $event?.value)"
            />

            <!-- Dynamic Filter Options -->
            <template v-for="(options, filterKey) in availableOptions" :key="filterKey">
                <SelectField
                    v-if="options.length > 0"
                    :label="filterKey.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())"
                    v-model="filters[filterKey]"
                    :options="options"
                    :disabled="loading"
                    placeholder="All"
                    @change="updateFilter(filterKey, $event?.value)"
                />
            </template>

            <!-- Search Field -->
            <TextField
                v-if="currentDataType === 'breeders' || currentDataType === 'institutes'"
                label="Search"
                v-model="filters.search"
                :disabled="loading"
                placeholder="Type to search..."
                :debounce="500"
                @input="updateFilter('search', $event)"
            />
        </div>

        <!-- Summary Statistics -->
        <div v-if="Object.keys(summaryData).length > 0" class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Summary</h4>
            <div class="grid grid-cols-2 gap-3">
                <div v-for="(value, key) in summaryData" :key="key" class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-lg font-semibold text-gray-900">{{ value }}</div>
                    <div class="text-xs text-gray-600 capitalize">
                        {{ key.replace(/total_|_/g, ' ').trim() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.map-data-filter-panel {
    position: relative;
    min-height: 400px;
}
</style>
