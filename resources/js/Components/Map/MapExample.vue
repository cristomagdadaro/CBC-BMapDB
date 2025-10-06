<script setup>
import { ref, onMounted } from 'vue'
import MapDataFilterPanel from '@/Components/Map/MapDataFilterPanel.vue'

const mapData = ref([])
const mapMetadata = ref({})
const currentFilters = ref({})

// Handle data updates from the filter panel
const handleDataUpdated = (result) => {
    mapData.value = result.data
    mapMetadata.value = result.metadata
    currentFilters.value = result.filters

    // Plot data on map
    plotDataOnMap(result.data)

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

// Plot data on your map (replace with your actual map plotting logic)
const plotDataOnMap = (data) => {
    // Example: Plot markers on your map
    data.forEach(point => {
        if (point.lat && point.lng) {
            // Add marker to map at point.lat, point.lng
            // Marker popup could show: point.label (point.total items)
            console.log(`Plot marker: ${point.label} (${point.total}) at ${point.lat}, ${point.lng}`)
        }
    })
}

onMounted(() => {
    console.log('Map component ready')
})
</script>

<template>
    <div class="flex gap-6 h-screen">
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
        <div class="flex-1 bg-gray-100 rounded-lg overflow-hidden">
            <div class="h-full relative">
                <!-- Your map component goes here -->
                <div class="absolute inset-0 flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <h3 class="text-lg font-medium mb-2">Map Visualization</h3>
                        <p class="text-sm">
                            Data Points: {{ mapData.length }}<br>
                            Current Filter: {{ currentFilters.data_type || 'None' }}<br>
                            Group By: {{ currentFilters.group_by || 'None' }}
                        </p>

                        <!-- Sample data display -->
                        <div v-if="mapData.length > 0" class="mt-4 max-h-64 overflow-y-auto">
                            <div class="text-left text-xs space-y-1">
                                <div v-for="point in mapData.slice(0, 10)" :key="point.label"
                                     class="bg-white p-2 rounded border">
                                    {{ point.label }}: {{ point.total }} items
                                    <span v-if="point.lat && point.lng" class="text-gray-500">
                                        ({{ point.lat }}, {{ point.lng }})
                                    </span>
                                </div>
                                <div v-if="mapData.length > 10" class="text-gray-500 text-center">
                                    ... and {{ mapData.length - 10 }} more
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Add any custom styles for your map container */
</style>
