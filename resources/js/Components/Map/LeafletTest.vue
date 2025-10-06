<script setup>
import { ref, onMounted } from 'vue'
import LeafletMap from '@/Components/Map/LeafletMap.vue'

// Mock data for testing the Leaflet map
const mockMapData = ref([
    {
        label: "Metro Manila",
        total: 150,
        lat: 14.5995,
        lng: 120.9842
    },
    {
        label: "Cebu City",
        total: 85,
        lat: 10.3157,
        lng: 123.8854
    },
    {
        label: "Davao City",
        total: 120,
        lat: 7.0731,
        lng: 125.6128
    },
    {
        label: "Iloilo City",
        total: 65,
        lat: 10.7202,
        lng: 122.5621
    },
    {
        label: "Baguio City",
        total: 45,
        lat: 16.4023,
        lng: 120.5960
    },
    {
        label: "Zamboanga City",
        total: 78,
        lat: 6.9214,
        lng: 122.0790
    },
    {
        label: "Cagayan de Oro",
        total: 92,
        lat: 8.4542,
        lng: 124.6319
    },
    {
        label: "Bacolod City",
        total: 55,
        lat: 10.6760,
        lng: 122.9500
    }
])

const selectedMarker = ref(null)
const mapComponent = ref(null)

// Handle marker clicks
const handleMarkerClick = (marker) => {
    selectedMarker.value = marker
    console.log('Marker clicked:', marker)
}

// Handle map ready
const handleMapReady = (map) => {
    console.log('Map is ready')
}

// Add random data point
const addRandomMarker = () => {
    const cities = ['Angeles City', 'Batangas City', 'Lipa City', 'Taguig City', 'Pasig City']
    const randomCity = cities[Math.floor(Math.random() * cities.length)]

    // Generate random coordinates within Philippines bounds
    const lat = 5 + Math.random() * 15
    const lng = 116 + Math.random() * 10
    const total = Math.floor(Math.random() * 200) + 1

    mockMapData.value.push({
        label: randomCity + ' (Random)',
        total,
        lat,
        lng
    })
}

// Remove selected marker
const removeSelectedMarker = () => {
    if (selectedMarker.value) {
        const index = mockMapData.value.findIndex(m =>
            m.lat === selectedMarker.value.position[0] &&
            m.lng === selectedMarker.value.position[1]
        )
        if (index > -1) {
            mockMapData.value.splice(index, 1)
            selectedMarker.value = null
        }
    }
}

// Fit map to all markers
const fitToMarkers = () => {
    if (mapComponent.value) {
        mapComponent.value.fitToMarkers()
    }
}

onMounted(() => {
    console.log('Leaflet test page loaded with', mockMapData.value.length, 'markers')
})
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Leaflet Map Test</h1>
                <p class="text-gray-600">Testing the Leaflet integration with mock data</p>
            </div>

            <!-- Controls -->
            <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-gray-700">
                            Markers: {{ mockMapData.length }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Total Items: {{ mockMapData.reduce((sum, m) => sum + m.total, 0) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            @click="addRandomMarker"
                            class="px-3 py-2 text-sm bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
                        >
                            Add Random Marker
                        </button>
                        <button
                            @click="removeSelectedMarker"
                            :disabled="!selectedMarker"
                            class="px-3 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Remove Selected
                        </button>
                        <button
                            @click="fitToMarkers"
                            class="px-3 py-2 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                        >
                            Fit to Markers
                        </button>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <LeafletMap
                    ref="mapComponent"
                    :map-data="mockMapData"
                    :center="[12.8797, 121.7740]"
                    :zoom="6"
                    height="600px"
                    @marker-click="handleMarkerClick"
                    @map-ready="handleMapReady"
                />
            </div>

            <!-- Selected Marker Details -->
            <div v-if="selectedMarker" class="mt-6 bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Selected Marker Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">Location</div>
                        <div class="font-medium text-gray-900">{{ selectedMarker.label }}</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">Total Count</div>
                        <div class="font-medium text-blue-600 text-xl">{{ selectedMarker.total }}</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">Coordinates</div>
                        <div class="font-mono text-sm text-gray-900">
                            {{ selectedMarker.position[0].toFixed(4) }}, {{ selectedMarker.position[1].toFixed(4) }}
                        </div>
                    </div>
                </div>

                <!-- Additional marker info -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">Marker Properties</h4>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Color:</span>
                            <span class="font-medium">{{ selectedMarker.color }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Size:</span>
                            <span class="font-medium">{{ selectedMarker.size }}px</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">ID:</span>
                            <span class="font-medium">{{ selectedMarker.id }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="mt-6 bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Map Data Points</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Coordinates
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(marker, index) in mockMapData" :key="index"
                                :class="{ 'bg-blue-50': selectedMarker && selectedMarker.position[0] === marker.lat && selectedMarker.position[1] === marker.lng }">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ marker.label }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ marker.total }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono text-gray-500">
                                        {{ marker.lat.toFixed(4) }}, {{ marker.lng.toFixed(4) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        @click="selectedMarker = { ...marker, position: [marker.lat, marker.lng], id: index }"
                                        class="text-blue-600 hover:text-blue-900 mr-3"
                                    >
                                        Select
                                    </button>
                                    <button
                                        @click="mockMapData.splice(index, 1)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom styles for the test page */
</style>
