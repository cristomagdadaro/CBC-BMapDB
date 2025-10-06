<script setup>
import { ref, onMounted } from 'vue'
import MapDataFilterPanel from '@/Components/Map/MapDataFilterPanel.vue'
import LeafletMap from '@/Components/Map/LeafletMap.vue'

const mapData = ref([])
const mapMetadata = ref({})
const currentFilters = ref({})
const selectedMarker = ref(null)
const mapComponent = ref(null)

// Handle data updates from the filter panel
const handleDataUpdated = (result) => {
    mapData.value = result.data
    mapMetadata.value = result.metadata
    currentFilters.value = result.filters

    console.log('Map data updated:', {
        dataPoints: result.data.length,
        dataType: result.filters.data_type,
        groupBy: result.filters.group_by
    })
}

// Handle filter changes
const handleFiltersChanged = (filters) => {
    currentFilters.value = filters
    console.log('Filters changed:', filters)
}

// Handle marker clicks on the map
const handleMarkerClick = (marker) => {
    selectedMarker.value = marker
    console.log('Marker clicked:', marker)

    // You can add custom logic here, like showing detailed information
    // or updating other parts of your application
}

// Handle map ready event
const handleMapReady = (map) => {
    console.log('Map is ready:', map)
}

// Fit map to show all data points
const fitMapToData = () => {
    if (mapComponent.value) {
        mapComponent.value.fitToMarkers()
    }
}

onMounted(() => {
    console.log('Enhanced map component ready')
})
</script>

<template>
    <div class="flex gap-6 h-screen bg-gray-50 p-6">
        <!-- Filter Panel -->
        <div class="w-80 flex-shrink-0">
            <MapDataFilterPanel
                :initial-data-type="'commodities'"
                :initial-filters="{ group_by: 'region' }"
                @data-updated="handleDataUpdated"
                @filters-changed="handleFiltersChanged"
            />
        </div>

        <!-- Map Container -->
        <div class="flex-1 flex flex-col gap-4">
            <!-- Map Header -->
            <div class="bg-white rounded-lg shadow-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Geographic Distribution</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Showing {{ mapData.length }} locations
                            <span v-if="currentFilters.data_type" class="ml-2">
                                • {{ currentFilters.data_type }} grouped by {{ currentFilters.group_by }}
                            </span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            @click="fitMapToData"
                            class="px-3 py-2 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                            :disabled="mapData.length === 0"
                        >
                            Fit to Data
                        </button>
                    </div>
                </div>
            </div>

            <!-- Leaflet Map -->
            <div class="flex-1 bg-white rounded-lg shadow-lg overflow-hidden">
                <LeafletMap
                    ref="mapComponent"
                    :map-data="mapData"
                    :center="[12.8797, 121.7740]"
                    :zoom="6"
                    height="100%"
                    @marker-click="handleMarkerClick"
                    @map-ready="handleMapReady"
                />
            </div>

            <!-- Selected Marker Details -->
            <div v-if="selectedMarker" class="bg-white rounded-lg shadow-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Selected Location</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Location</div>
                        <div class="font-medium">{{ selectedMarker.label }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Total Count</div>
                        <div class="font-medium text-blue-600">{{ selectedMarker.total }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Coordinates</div>
                        <div class="font-mono text-xs">
                            {{ selectedMarker.position[0].toFixed(4) }}, {{ selectedMarker.position[1].toFixed(4) }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Data Type</div>
                        <div class="font-medium capitalize">{{ currentFilters.data_type }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom styles for the enhanced map layout */
</style>
