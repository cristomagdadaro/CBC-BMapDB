<template>
    <div class="flex flex-col gap-2">
        <div v-if="label" class="flex justify-between items-center px-1 gap-2 min-h-[1.25rem]">
            <label
                class="flex gap-0.5 items-center whitespace-nowrap text-sm font-medium text-gray-700 cursor-pointer"
                :class="{ 'text-red-600': !!error }"
            >
                {{ label }}
                <span v-if="required" class="text-red-500 font-bold text-sm ml-1" aria-label="required">*</span>
            </label>
            <div class="flex items-center gap-2">
                <button
                    v-if="title"
                    type="button"
                    :title="title"
                    :aria-label="`Help: ${title}`"
                    class="text-gray-600 opacity-70 font-bold text-[0.6rem] leading-none border border-gray-400 rounded-full p-0.5 px-1 hover:bg-emerald-700 hover:text-white hover:border-emerald-700 transition-colors duration-200 cursor-help focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
                >
                    ?
                </button>
            </div>
        </div>
        
        <!-- Toggle Mode -->
        <div class="flex items-center gap-4 mb-2">
            <label class="inline-flex items-center cursor-pointer">
                <input type="radio" v-model="internalValue.type" value="city" class="form-radio text-emerald-600 focus:ring-emerald-500" @change="emitUpdate">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select City/Municipality</span>
            </label>
            <label class="inline-flex items-center cursor-pointer">
                <input type="radio" v-model="internalValue.type" value="custom" class="form-radio text-emerald-600 focus:ring-emerald-500" @change="emitUpdate">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Drop Custom Pin</span>
            </label>
        </div>

        <!-- City Select Mode -->
        <div v-if="internalValue.type === 'city'">
            <select-search-field 
                :api-link="apiLink" 
                :error="error" 
                v-model="internalValue.city_id" 
                @update:modelValue="emitUpdate"
                placeholder="Search for a city or municipality..."
            />
        </div>

        <!-- Custom Map Point Mode -->
        <div v-else class="space-y-3">
            <Teleport to="body" :disabled="!isFullscreen">
                <div :class="isFullscreen ? 'fixed inset-0 z-[9999] h-screen w-screen rounded-none' : 'h-64 w-full relative z-0 rounded-lg border border-gray-300 overflow-hidden'" class="bg-gray-100">
                <l-map 
                    ref="mapRef"
                    :zoom="zoom" 
                    :center="center" 
                    @click="onMapClick"
                    :useGlobalLeaflet="false"
                    @ready="onMapReady"
                >
                    <l-tile-layer
                        url="https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}"
                        attribution="Tiles &copy; Esri &mdash; Source: Esri"
                    ></l-tile-layer>
                    
                    <l-marker 
                        v-if="internalValue.lat && internalValue.lng"
                        :lat-lng="[internalValue.lat, internalValue.lng]"
                        draggable
                        @dragend="onMarkerDragEnd"
                    ></l-marker>
                    <l-control position="topright">
                        <button 
                            type="button" 
                            @click.prevent="toggleFullscreen"
                            class="bg-white rounded-sm p-1.5 shadow-md border border-gray-300 hover:bg-gray-100 flex items-center justify-center text-gray-700 hover:text-black focus:outline-none"
                            :title="isFullscreen ? 'Exit Fullscreen' : 'View Fullscreen'"
                        >
                            <svg v-if="!isFullscreen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h6m0 0v6m0-6l-7 7m17-11h-6m0 0V4m0 6l7-7m-7 17l7-7m-7 7v-6m0-14l-7 7m7-7v6" />
                            </svg>
                        </button>
                    </l-control>
                </l-map>
                </div>
            </Teleport>
            
            <div class="text-xs text-gray-500">
                Click on the map to place a pin, or drag the existing pin.
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Location Name (Required)</label>
                    <input 
                        type="text" 
                        v-model="internalValue.name" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                        placeholder="e.g. North Demo Farm"
                        @input="emitUpdate"
                    >
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                        <input type="text" readonly :value="internalValue.lat" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                        <input type="text" readonly :value="internalValue.lng" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm">
                    </div>
                </div>
            </div>
        </div>
        
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, nextTick } from 'vue';
import { LMap, LTileLayer, LMarker, LControl } from '@vue-leaflet/vue-leaflet';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import SelectSearchField from '@/Components/Form/SelectSearchField.vue';

// Fix for missing Leaflet marker icons in Vue 3 / Vite
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow
});

const props = defineProps({
    modelValue: {
        type: [String, Number, Object],
        default: null
    },
    label: {
        type: String,
        default: ''
    },
    error: {
        type: String,
        default: ''
    },
    apiLink: {
        type: String,
        required: true
    },
    required: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: null
    }
});

const emit = defineEmits(['update:modelValue']);

// Default map view (Philippines)
const center = ref([12.8797, 121.7740]);
const zoom = ref(5);
const mapRef = ref(null);
const leafletMap = ref(null);

const isFullscreen = ref(false);

const onMapReady = (mapObject) => {
    leafletMap.value = mapObject;
};

const toggleFullscreen = () => {
    isFullscreen.value = !isFullscreen.value;
    
    // Invalidate map size after DOM updates to fix rendering without destroying the map
    setTimeout(() => {
        if (leafletMap.value) {
            leafletMap.value.invalidateSize();
        } else if (mapRef.value && mapRef.value.leafletObject) {
            mapRef.value.leafletObject.invalidateSize();
        }
    }, 100);
};

const internalValue = ref({
    type: 'city',
    city_id: null,
    lat: null,
    lng: null,
    name: ''
});

// Initialize from props
onMounted(() => {
    if (props.modelValue) {
        if (typeof props.modelValue === 'object' && props.modelValue !== null) {
            internalValue.value = { ...internalValue.value, ...props.modelValue };
            if (internalValue.value.lat && internalValue.value.lng) {
                center.value = [internalValue.value.lat, internalValue.value.lng];
                zoom.value = 12;
            }
        } else {
            // It's just a city ID from the old format
            internalValue.value.type = 'city';
            internalValue.value.city_id = props.modelValue;
        }
    }
});

const emitUpdate = () => {
    // If they are in city mode, we can just emit the ID like it used to, 
    // or emit the object depending on backend support.
    // Emitting object allows backend to seamlessly handle both.
    emit('update:modelValue', internalValue.value);
};

const onMapClick = (e) => {
    internalValue.value.lat = e.latlng.lat;
    internalValue.value.lng = e.latlng.lng;
    emitUpdate();
};

const onMarkerDragEnd = (e) => {
    const latlng = e.target.getLatLng();
    internalValue.value.lat = latlng.lat;
    internalValue.value.lng = latlng.lng;
    emitUpdate();
};
</script>
