<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import { LMap, LTileLayer, LMarker, LPopup, LIcon } from '@vue-leaflet/vue-leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
    mapData: {
        type: Array,
        default: () => []
    },
    center: {
        type: Array,
        default: () => [12.8797, 121.7740] // Philippines center
    },
    zoom: {
        type: Number,
        default: 6
    },
    height: {
        type: String,
        default: '500px'
    },
    clustered: {
        type: Boolean,
        default: true
    },
    showHeatmap: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['markerClick', 'mapReady'])

// Map instance and state
const map = ref(null)
const mapReady = ref(false)
const currentCenter = ref(props.center)
const currentZoom = ref(props.zoom)

// Marker management
const markers = ref([])
const selectedMarker = ref(null)

// Generate marker colors based on data value
const getMarkerColor = (total, maxTotal) => {
    const intensity = total / maxTotal
    if (intensity > 0.8) return '#dc2626' // red-600
    if (intensity > 0.6) return '#ea580c' // orange-600
    if (intensity > 0.4) return '#ca8a04' // yellow-600
    if (intensity > 0.2) return '#65a30d' // lime-600
    return '#16a34a' // green-600
}

// Get marker size based on data value
const getMarkerSize = (total, maxTotal) => {
    const intensity = total / maxTotal
    const baseSize = 10
    const maxSize = 30
    return Math.max(baseSize, Math.min(maxSize, baseSize + (intensity * (maxSize - baseSize))))
}

// Process map data into markers
const processMapData = () => {
    if (!props.mapData || props.mapData.length === 0) {
        markers.value = []
        return
    }

    const maxTotal = Math.max(...props.mapData.map(item => item.total || 0))

    markers.value = props.mapData
        .filter(item => item.lat && item.lng && !isNaN(item.lat) && !isNaN(item.lng))
        .map((item, index) => ({
            id: index,
            position: [parseFloat(item.lat), parseFloat(item.lng)],
            label: item.label || 'Unknown',
            total: item.total || 0,
            color: getMarkerColor(item.total || 0, maxTotal),
            size: getMarkerSize(item.total || 0, maxTotal),
            data: item
        }))
}

// Handle marker click
const onMarkerClick = (marker) => {
    selectedMarker.value = marker
    emit('markerClick', marker)
}

// Fit map to show all markers
const fitMapToMarkers = () => {
    if (!map.value || markers.value.length === 0) return

    nextTick(() => {
        try {
            const leafletObject = map.value.leafletObject
            if (leafletObject && markers.value.length > 0) {
                const group = new L.featureGroup(
                    markers.value.map(m => L.marker(m.position))
                )
                leafletObject.fitBounds(group.getBounds().pad(0.1))
            }
        } catch (error) {
            console.warn('Could not fit map to markers:', error)
        }
    })
}

// Handle map ready event
const onMapReady = () => {
    mapReady.value = true
    emit('mapReady', map.value)

    // Fit map to markers after a short delay
    setTimeout(() => {
        fitMapToMarkers()
    }, 500)
}

// Custom marker icon
const createCustomIcon = (color, size) => {
    return {
        iconUrl: `data:image/svg+xml;base64,${btoa(`
            <svg width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" fill="${color}" stroke="white" stroke-width="2"/>
                <circle cx="12" cy="12" r="4" fill="white"/>
            </svg>
        `)}`,
        iconSize: [size, size],
        iconAnchor: [size/2, size/2],
        popupAnchor: [0, -(size/2)]
    }
}

// Watch for data changes
watch(() => props.mapData, () => {
    processMapData()

    // Fit map to new markers after processing
    nextTick(() => {
        if (markers.value.length > 0) {
            fitMapToMarkers()
        }
    })
}, { deep: true, immediate: true })

// Expose methods
defineExpose({
    fitToMarkers: fitMapToMarkers,
    getMap: () => map.value,
    getMarkers: () => markers.value,
    setCenter: (lat, lng) => {
        currentCenter.value = [lat, lng]
    },
    setZoom: (zoom) => {
        currentZoom.value = zoom
    }
})

onMounted(() => {
    processMapData()
})
</script>

<template>
    <div class="leaflet-map-container" :style="{ height }">
        <LMap
            ref="map"
            v-model:zoom="currentZoom"
            v-model:center="currentCenter"
            :use-global-leaflet="false"
            @ready="onMapReady"
            class="h-full w-full rounded-lg overflow-hidden"
        >
            <!-- Tile Layer - OpenStreetMap -->
            <LTileLayer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                attribution='&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
                :max-zoom="18"
            />

            <!-- Markers -->
            <LMarker
                v-for="marker in markers"
                :key="marker.id"
                :lat-lng="marker.position"
                @click="onMarkerClick(marker)"
            >
                <!-- Custom Icon -->
                <LIcon
                    :icon-url="createCustomIcon(marker.color, marker.size).iconUrl"
                    :icon-size="createCustomIcon(marker.color, marker.size).iconSize"
                    :icon-anchor="createCustomIcon(marker.color, marker.size).iconAnchor"
                    :popup-anchor="createCustomIcon(marker.color, marker.size).popupAnchor"
                />

                <!-- Popup -->
                <LPopup>
                    <div class="p-2 min-w-[200px]">
                        <h3 class="font-semibold text-gray-900 mb-2">{{ marker.label }}</h3>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-medium">{{ marker.total }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Location:</span>
                                <span class="text-xs text-gray-500">
                                    {{ marker.position[0].toFixed(4) }}, {{ marker.position[1].toFixed(4) }}
                                </span>
                            </div>
                        </div>

                        <!-- Additional data if available -->
                        <div v-if="marker.data.description" class="mt-2 pt-2 border-t border-gray-200">
                            <p class="text-xs text-gray-600">{{ marker.data.description }}</p>
                        </div>
                    </div>
                </LPopup>
            </LMarker>
        </LMap>

        <!-- Map Controls Overlay -->
        <div class="absolute top-4 right-4 bg-white rounded-lg shadow-lg p-3 space-y-2 z-[1000]">
            <div class="text-xs text-gray-600">
                <div class="font-medium mb-1">Map Data</div>
                <div>Markers: {{ markers.length }}</div>
                <div v-if="markers.length > 0">
                    Total Items: {{ markers.reduce((sum, m) => sum + m.total, 0) }}
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <button
                    @click="fitMapToMarkers"
                    class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
                    :disabled="markers.length === 0"
                >
                    Fit to Data
                </button>
            </div>
        </div>

        <!-- Legend -->
        <div v-if="markers.length > 0" class="absolute bottom-4 left-4 bg-white rounded-lg shadow-lg p-3 z-[1000]">
            <div class="text-xs font-medium text-gray-900 mb-2">Legend</div>
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-600"></div>
                    <span class="text-xs text-gray-600">High (80%+)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-orange-600"></div>
                    <span class="text-xs text-gray-600">Medium-High (60-80%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-yellow-600"></div>
                    <span class="text-xs text-gray-600">Medium (40-60%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-lime-600"></div>
                    <span class="text-xs text-gray-600">Medium-Low (20-40%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-green-600"></div>
                    <span class="text-xs text-gray-600">Low (0-20%)</span>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="!mapReady" class="absolute inset-0 bg-gray-100 flex items-center justify-center rounded-lg">
            <div class="text-gray-600">Loading map...</div>
        </div>

        <!-- Empty State -->
        <div v-if="mapReady && markers.length === 0" class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-gray-500">
                <div class="text-lg font-medium mb-2">No Data to Display</div>
                <div class="text-sm">Apply filters to see data on the map</div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.leaflet-map-container {
    position: relative;
    background-color: #f3f4f6;
}

/* Ensure Leaflet controls have proper z-index */
:deep(.leaflet-control-container) {
    z-index: 999;
}
</style>
