<!-- Default: Clustering enabled
<LeafletMap :mapData="yourData" />

Heatmap mode
<LeafletMap :mapData="yourData" :showHeatmap="true" :clustered="false" />

Individual markers
<LeafletMap :mapData="yourData" :clustered="false" :showHeatmap="false" />
 -->
<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import { LMap, LTileLayer, LMarker, LPopup, LIcon } from '@vue-leaflet/vue-leaflet'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

// Import clustering and heatmap plugins
import 'leaflet.markercluster/dist/MarkerCluster.css'
import 'leaflet.markercluster/dist/MarkerCluster.Default.css'
import 'leaflet.markercluster'
import 'leaflet.heat'
import axios from 'axios'
import OrbitOverlay from '@/Components/Map/OrbitOverlay.vue'

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
    },
    defaultTileProvider: {
        type: String,
        default: 'CartoDB Voyager'
    },
    dataType: {
        type: String,
        default: 'commodities'
    }
})

const emit = defineEmits(['markerClick', 'mapReady'])

// Map instance and state
const map = ref(null)
const mapReady = ref(false)
const currentCenter = ref(props.center)
const currentZoom = ref(props.zoom)

// Clustering and heatmap layers
const markerClusterGroup = ref(null)
const heatmapLayer = ref(null)

// Tile provider management
const tileProviders = ref([
    { name: 'CartoDB Voyager', visible: true, url: 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>' },
    { name: 'CartoDB VoyagerNoLabels', visible: false, attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>', url: 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_nolabels/{z}/{x}/{y}{r}.png' },
    { name: 'CartoDB DarkMatter', visible: false, url: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>' },
    { name: 'CartoDB DarkMatterNoLabels', visible: false, url: 'https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png', attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>' },
    { name: 'Esri WorldGrayCanvas', visible: false, url: 'https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}', attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ' },
    { name: 'OpenStreetMap', visible: false, url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' },
    { name: 'Esri WorldImagery', visible: false, url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community' },
    { name: 'CartoDB Positron', visible: false, url: 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>' }
])

const currentTileProvider = ref(null)
const showTileSelector = ref(false)

// Marker management
const markers = ref([])
const selectedMarker = ref(null)

// Cache marker icons to avoid re-creating data URLs every render
const iconCache = new Map()
const raf = typeof requestAnimationFrame === 'function' ? requestAnimationFrame : (cb) => setTimeout(cb, 16)
const caf = typeof cancelAnimationFrame === 'function' ? cancelAnimationFrame : clearTimeout
const getCachedIcon = (color, size) => {
    const key = `${color}-${size}`
    if (!iconCache.has(key)) {
        iconCache.set(key, createCustomIcon(color, size))
    }
    return iconCache.get(key)
}

// Debounce map layer rebuilds to prevent thrashing on rapid prop changes
let layerUpdateHandle = null
const scheduleLayerUpdate = () => {
    if (layerUpdateHandle) caf(layerUpdateHandle)
    layerUpdateHandle = raf(() => {
        updateMapLayers()
        layerUpdateHandle = null
    })
}

// Generate marker colors based on data value
const getMarkerColor = (total, maxTotal) => {
    const safeMax = Math.max(maxTotal, 1)
    const intensity = total / safeMax
    if (intensity > 0.8) return '#dc2626' // red-600
    if (intensity > 0.6) return '#ea580c' // orange-600
    if (intensity > 0.4) return '#ca8a04' // yellow-600
    if (intensity > 0.2) return '#65a30d' // lime-600
    return '#16a34a' // green-600
}

// Get marker size based on data value
const getMarkerSize = (total, maxTotal) => {
    const safeMax = Math.max(maxTotal, 1)
    const intensity = total / safeMax
    const baseSize = 10
    const maxSize = 30
    return Math.max(baseSize, Math.min(maxSize, baseSize + (intensity * (maxSize - baseSize))))
}

// Orbit overlay state and caching
const orbitVisible = ref(false)
const orbitLoading = ref(false)
const orbitItems = ref([])
const orbitX = ref(0)
const orbitY = ref(0)
const orbitLocationName = ref('')
const orbitRadius = ref(80)
const orbitCache = new Map()
let orbitHideTimer = null

let batchRequestQueue = []
let batchTimeout = null

const processBatchRequest = async () => {
    if (batchRequestQueue.length === 0) return

    const requests = [...batchRequestQueue]
    batchRequestQueue = []

    const cityIdsToFetch = requests.map(req => req.cityId).filter(id => id)

    if (cityIdsToFetch.length === 0) {
        // Resolve all promises with empty arrays if there are no valid IDs
        requests.forEach(req => req.resolve([]))
        return
    }

    try {
        const { data } = await axios.get('/api/map-data/orbit-items', {
            params: {
                data_type: props.dataType,
                city_ids: cityIdsToFetch.join(','),
                limit: 20
            }
        })

        const results = data.data || {}

        // Update cache and resolve promises
        Object.entries(results).forEach(([cityId, items]) => {
            const key = cacheKey(cityId)
            orbitCache.set(key, items)
        })

        requests.forEach(req => {
            const key = cacheKey(req.cityId)
            req.resolve(orbitCache.get(key) || [])
        })

    } catch (e) {
        console.error('Failed to fetch batch orbit items', e)
        requests.forEach(req => req.reject(e))
    }
}

const enqueueFetch = (cityId) => {
    return new Promise((resolve, reject) => {
        const key = cacheKey(cityId)
        if (orbitCache.has(key)) {
            resolve(orbitCache.get(key))
            return
        }

        // Add to queue if not already there
        if (!batchRequestQueue.some(req => req.cityId === cityId)) {
            batchRequestQueue.push({ cityId, resolve, reject })
        }

        // Debounce the processing
        clearTimeout(batchTimeout)
        batchTimeout = setTimeout(processBatchRequest, 50) // 50ms debounce window
    })
}

const cacheKey = (cityId) => `${props.dataType}:${cityId}`

const cancelHide = () => {
    if (orbitHideTimer) {
        clearTimeout(orbitHideTimer)
        orbitHideTimer = null
    }
}

const hideOrbit = (immediate = false) => {
    cancelHide()
    if (immediate) {
        orbitVisible.value = false
        orbitItems.value = []
        orbitLoading.value = false
        orbitLocationName.value = ''
        return
    }
    orbitHideTimer = setTimeout(() => {
        orbitVisible.value = false
        orbitItems.value = []
        orbitLoading.value = false
        orbitLocationName.value = ''
    }, 300)
}

const fetchOrbitItems = async (cityId) => {
    return enqueueFetch(cityId)
}

const prefetchNearby = (marker) => {
    // Prefetch up to 4 nearest neighbors not cached yet
    if (!map.value?.leafletObject) return
    const cityId = marker.data?.city_id || marker.cityId || marker.data?.cityId
    if (!cityId) return

    const base = L.latLng(marker.position)
    const candidates = markers.value
        .filter(m => (m.data?.city_id || m.cityId || m.data?.cityId) && m !== marker)
        .map(m => ({ m, d: base.distanceTo(L.latLng(m.position)) }))
        .sort((a, b) => a.d - b.d)
        .slice(0, 4)
        .map(x => x.m)

    candidates.forEach((m) => {
        const id = m.data?.city_id || m.cityId || m.data?.cityId
        const key = cacheKey(id)
        if (!orbitCache.has(key)) {
            enqueueFetch(id).catch(() => { /* ignore errors in prefetch */ })
        }
    })
}

const showOrbitForMarker = async (marker) => {
    if (!map.value?.leafletObject) {
        return
    }
    const cityId = marker.data?.city_id || marker.cityId || marker.data?.cityId
    if (!cityId) {
        console.warn('No cityId found for marker; cannot load orbit items.', marker.data)
        return
    }

    // position overlay at marker's container point
    const pt = map.value.leafletObject.latLngToContainerPoint(marker.position)
    orbitX.value = pt.x
    orbitY.value = pt.y
    orbitLocationName.value = marker.label
    orbitVisible.value = true
    orbitLoading.value = true

    const items = await fetchOrbitItems(cityId)
    orbitItems.value = items
    orbitLoading.value = false

    // Preload nearby for smoother next hovers
    prefetchNearby(marker)
}

// Tile provider management functions
const initializeTileProvider = () => {
    // Set default tile provider based on prop
    const defaultProvider = tileProviders.value.find(p => p.name === props.defaultTileProvider)
    if (defaultProvider) {
        setActiveTileProvider(defaultProvider.name)
    } else {
        // Fallback to first provider if default not found
        setActiveTileProvider(tileProviders.value[0].name)
    }
}

const setActiveTileProvider = (providerName) => {
    // Set all providers to invisible
    tileProviders.value.forEach(provider => {
        provider.visible = false
    })

    // Set selected provider to visible
    const selectedProvider = tileProviders.value.find(p => p.name === providerName)
    if (selectedProvider) {
        selectedProvider.visible = true
        currentTileProvider.value = selectedProvider
    }

    // Close selector
    showTileSelector.value = false
}

const toggleTileSelector = () => {
    showTileSelector.value = !showTileSelector.value
}

// Clustering and heatmap management
const initializeClustering = () => {
    if (!map.value?.leafletObject) return

    // Remove existing cluster group if it exists
    if (markerClusterGroup.value) {
        map.value.leafletObject.removeLayer(markerClusterGroup.value)
    }

    if (props.clustered && markers.value.length > 0) {
        // Create marker cluster group with custom options
        markerClusterGroup.value = L.markerClusterGroup({
            chunkedLoading: true,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            maxClusterRadius: 80,
            iconCreateFunction: function(cluster) {
                const count = cluster.getChildCount()
                let size = 40
                let className = 'marker-cluster-small'

                if (count < 10) {
                    size = 40
                    className = 'marker-cluster-small'
                } else if (count < 100) {
                    size = 50
                    className = 'marker-cluster-medium'
                } else {
                    size = 60
                    className = 'marker-cluster-large'
                }

                return new L.DivIcon({
                    html: `<div><span>${count}</span></div>`,
                    className: `marker-cluster ${className}`,
                    iconSize: new L.Point(size, size)
                })
            }
        })

        // Add markers to cluster group
        markers.value.forEach(marker => {
            const leafletMarker = L.marker(marker.position, {
                icon: L.divIcon({
                    html: `<div style="background-color: ${marker.color}; width: ${marker.size}px; height: ${marker.size}px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></div>`,
                    className: 'custom-marker',
                    iconSize: [marker.size, marker.size],
                    iconAnchor: [marker.size/2, marker.size/2]
                })
            })

            // attach meta for cluster reverse lookup
            // @ts-ignore
            leafletMarker.myMeta = marker
            // NEW: Assign the unique ID to the marker instance
            // @ts-ignore
            leafletMarker.uniqueId = marker.id

            leafletMarker.bindPopup(`
                <div class="p-2 min-w-[200px]">
                    <h3 class="font-semibold text-gray-900 mb-2">${marker.label}</h3>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total:</span>
                            <span class="font-medium">${marker.total}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Location:</span>
                            <span class="text-xs text-gray-500">
                                ${marker.position[0].toFixed(4)}, ${marker.position[1].toFixed(4)}
                            </span>
                        </div>
                    </div>
                    ${marker.data.description ? `
                        <div class="mt-2 pt-2 border-t border-gray-200">
                            <p class="text-xs text-gray-600">${marker.data.description}</p>
                        </div>
                    ` : ''}
                </div>
            `)

            leafletMarker.on('click', () => {
                onMarkerClick(marker)
            })
            leafletMarker.on('mouseover', (e) => {
                //console.log('Marker mouseover event:', e)
                showOrbitForMarker(marker)
            })
            leafletMarker.on('mouseout', () => {
                //console.log('Marker mouseout event.')
                hideOrbit()
            })

            markerClusterGroup.value.addLayer(leafletMarker)
        })

        // Cluster hover: if all children in a cluster share the same cityId, show orbit at cluster center
        markerClusterGroup.value.on('clustermouseover', (e) => {
            try {
                const children = e.layer.getAllChildMarkers?.() || []
                const metas = children.map(ch => ch.myMeta).filter(Boolean)
                const getId = (m) => m?.data?.city_id ?? m?.cityId ?? m?.data?.cityId
                const ids = Array.from(new Set(metas.map(getId).filter(Boolean)))

                if (ids.length === 1) {
                    const markerMeta = metas[0]
                    // position at cluster center
                    const ll = e.layer.getLatLng()
                    const synthetic = { ...markerMeta, position: [ll.lat, ll.lng] }
                    showOrbitForMarker(synthetic)
                }
            } catch (err) {
                console.error('Error in clustermouseover handler:', err)
            }
        })
        markerClusterGroup.value.on('clustermouseout', () => hideOrbit())

        map.value.leafletObject.addLayer(markerClusterGroup.value)

        // ensure hover events are bound to child markers (safety)
        attachHoverEventsToClusterMarkers()
    }
}

const attachHoverEventsToClusterMarkers = () => {
    if (!markerClusterGroup.value) return
    try {
        markerClusterGroup.value.eachLayer(layer => {
            if (layer instanceof L.Marker && !layer.getPopup()) {
                // already added above; keep as safety
                layer.on('mouseover', () => {
                    // @ts-ignore
                    const uniqueId = layer.uniqueId
                    const marker = markers.value.find(m => m.id === uniqueId)
                    if (marker) showOrbitForMarker(marker)
                })
                layer.on('mouseout', () => hideOrbit())
            }
        })
    } catch (_) { /* noop */ }
}

const initializeHeatmap = () => {
    if (!map.value?.leafletObject) return

    // Remove existing heatmap if it exists
    if (heatmapLayer.value) {
        map.value.leafletObject.removeLayer(heatmapLayer.value)
        heatmapLayer.value = null
    }

    if (props.showHeatmap && markers.value.length > 0) {
        // Prepare heatmap data: [lat, lng, intensity]
        const maxTotal = Math.max(...markers.value.map(m => m.total))
        const heatmapData = markers.value.map(marker => [
            marker.position[0], // lat
            marker.position[1], // lng
            marker.total / maxTotal // normalized intensity (0-1)
        ])

        heatmapLayer.value = L.heatLayer(heatmapData, {
            radius: 25,
            blur: 15,
            maxZoom: 17,
            gradient: {
                0.0: '#3b82f6', // blue
                0.2: '#10b981', // green
                0.4: '#f59e0b', // yellow
                0.6: '#f97316', // orange
                0.8: '#ef4444', // red
                1.0: '#dc2626'  // dark red
            }
        })

        map.value.leafletObject.addLayer(heatmapLayer.value)
    }
}

const updateMapLayers = () => {
    nextTick(() => {
        if (props.showHeatmap) {
            initializeHeatmap()
        } else {
            initializeClustering()
        }
    })
}

// Process map data into markers
const processMapData = () => {
    const incoming = Array.isArray(props.mapData) ? props.mapData : []

    if (incoming.length === 0) {
        markers.value = []
        return
    }

    let maxTotal = 0
    const processed = []

    incoming.forEach((item, index) => {
        const lat = parseFloat(item.lat)
        const lng = parseFloat(item.lng)
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return

        const total = Number(item.total) || 0
        if (total > maxTotal) maxTotal = total

        processed.push({
            id: `${props.dataType}-${item.id || index}`,
            position: [lat, lng],
            label: item.label || 'Unknown',
            total,
            data: item,
            cityId: item.city_id || item.cityId || null,
        })
    })

    markers.value = processed.map((item) => ({
        ...item,
        color: getMarkerColor(item.total, maxTotal),
        size: getMarkerSize(item.total, maxTotal),
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
                // Validate that we have valid coordinates
                const validMarkers = markers.value.filter(m =>
                    m.position &&
                    Array.isArray(m.position) &&
                    m.position.length === 2 &&
                    !isNaN(m.position[0]) &&
                    !isNaN(m.position[1]) &&
                    isFinite(m.position[0]) &&
                    isFinite(m.position[1])
                )

                if (validMarkers.length === 0) {
                    console.warn('No valid markers found for bounds calculation')
                    return
                }

                if (validMarkers.length === 1) {
                    // If only one marker, just center on it
                    leafletObject.setView(validMarkers[0].position, 10)
                    return
                }

                // Create bounds from valid markers
                const bounds = L.latLngBounds(validMarkers.map(m => m.position))

                // Check if bounds are valid
                if (bounds.isValid()) {
                    leafletObject.fitBounds(bounds, {
                        padding: [20, 20],
                        maxZoom: 15
                    })
                } else {
                    console.warn('Calculated bounds are not valid')
                    // Fallback to Philippines center
                    leafletObject.setView([12.8797, 121.7740], 6)
                }
            }
        } catch (error) {
            // Fallback to Philippines center if bounds fail
            if (map.value?.leafletObject) {
                map.value.leafletObject.setView([12.8797, 121.7740], 6)
            }
        }
    })
}

// Handle map ready event
const onMapReady = () => {
    mapReady.value = true
    emit('mapReady', map.value)

    // Initialize map layers
    updateMapLayers()

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

// Watch props
watch([() => props.clustered, () => props.showHeatmap], () => {
    scheduleLayerUpdate()

    // Ensure hover handlers are attached after cluster group rebuild
    nextTick(() => attachHoverEventsToClusterMarkers())
})

watch(() => props.dataType, () => {
    // Clear orbit and cache when switching data types
    orbitVisible.value = false
    orbitCache.clear()
})

watch(() => props.mapData, () => {
    // When data changes (filters), rebuild markers and layers
    processMapData()
    scheduleLayerUpdate()
}, { deep: true })

// Hide overlay on map move/zoom
watch(mapReady, () => {
    if (map.value?.leafletObject) {
        map.value.leafletObject.on('movestart zoomstart', () => hideOrbit(true))
    }
})

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
    initializeTileProvider()
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
            <!-- Tile Layer - Only render the active/visible provider -->
            <LTileLayer
                v-if="currentTileProvider"
                :key="currentTileProvider.name"
                :url="currentTileProvider.url"
                :attribution="currentTileProvider.attribution"
                :max-zoom="18"
            />

            <!-- Markers - Only show when not clustering and not showing heatmap -->
                <template v-if="!clustered && !showHeatmap">
                    <LMarker
                        v-for="marker in markers"
                        :key="marker.id"
                        :lat-lng="marker.position"
                        @click="onMarkerClick(marker)"
                        @mouseover="showOrbitForMarker(marker)"
                        @mouseout="hideOrbit()"
                    >
                        <!-- Custom Icon -->
                        <LIcon v-bind="getCachedIcon(marker.color, marker.size)" />

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
                </template>
        </LMap>

        <!-- Orbit overlay on hover -->
        <OrbitOverlay
            :visible="orbitVisible"
            :loading="orbitLoading"
            :items="orbitItems"
            :x="orbitX"
            :y="orbitY"
            :radius="orbitRadius"
            :location-name="orbitLocationName"
            :data-type="dataType"
            @close="hideOrbit()"
            @enter="cancelHide()"
        />

        <!-- Map Controls Overlay -->
        <div class="absolute top-4 right-4 bg-white rounded-lg shadow-lg p-3 space-y-2 z-[1000]">
            <div class="text-xs text-gray-600">
                <div class="font-medium mb-1">Map Data</div>
                <div>Markers: {{ markers.length }}</div>
                <div v-if="markers.length > 0">
                    Total Items: {{ markers.reduce((sum, m) => sum + m.total, 0) }}
                </div>
                <div class="mt-2 text-xs">
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full" :class="clustered ? 'bg-green-500' : 'bg-gray-300'"></span>
                        <span>Clustering: {{ clustered ? 'On' : 'Off' }}</span>
                    </div>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="w-2 h-2 rounded-full" :class="showHeatmap ? 'bg-blue-500' : 'bg-gray-300'"></span>
                        <span>Heatmap: {{ showHeatmap ? 'On' : 'Off' }}</span>
                    </div>
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

        <!-- Tile Provider Selector (Collapsible) -->
        <div v-if="tileProviders.length > 1" class="absolute top-3 left-14 bg-white rounded-lg shadow-lg z-[1000]">
            <!-- Header Button -->
            <button
                @click="showTileSelector = !showTileSelector"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-50 rounded-lg transition-colors"
            >
                <div class="flex items-center gap-2">
                    <span class="text-xs">🗺️</span>
                    <span>{{ currentTileProvider?.name || 'Map Style' }}</span>
                </div>
                <svg
                    class="w-4 h-4 transition-transform duration-200"
                    :class="{ 'rotate-180': showTileSelector }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Collapsible Content -->
            <div v-if="showTileSelector" class="border-t border-gray-100 p-2">
                <div class="text-xs text-gray-500 mb-2 px-1">Choose map style:</div>
                <div class="flex flex-col gap-1 max-h-60 overflow-y-auto">
                    <button
                        v-for="provider in tileProviders"
                        :key="provider.name"
                        @click="setActiveTileProvider(provider.name)"
                        class="flex items-center justify-between text-left w-full px-2 py-1.5 rounded transition-all text-sm"
                        :class="{
                            'bg-blue-500 text-white': provider.visible,
                            'bg-gray-50 text-gray-900 hover:bg-gray-100': !provider.visible
                        }"
                    >
                        <span>{{ provider.name }}</span>
                        <span v-if="provider.visible" class="text-xs">✓</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div v-if="markers.length > 0" class="absolute bottom-4 left-4 bg-white rounded-lg shadow-lg p-3 z-[1000]">
            <div class="text-xs font-medium text-gray-900 mb-2">Density Legend</div>
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
