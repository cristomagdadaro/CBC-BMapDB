<script>
import MapDataFilterPanel from "@/Components/Map/MapDataFilterPanel.vue";
import LeafletMap from "@/Components/Map/LeafletMap.vue";

export default {
    name: "EnhancedMapView",

    components: {
        MapDataFilterPanel,
        LeafletMap,
    },

    props: {
        initialDataType: {
            type: String,
            default: "breeders",
        },
        initialFilters: {
            type: Object,
            default: () => ({}),
        },
        tableList: {
            type: Array,
            default: () => [],
        },
        model: {
            type: [Object, Function],
            default: null,
        },
        customPoint: {
            type: [Array, Object, null],
            default: null,
        },
        offline: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            mapData: [],
            mapMetadata: {},
            currentFilters: {},
            selectedMarker: null,
            mapComponent: null,
            showFilters: false,
        };
    },

    mounted() {
        if (this.offline || this.customPoint) {
            this.applyCustomPoints();
        }

        console.log("Enhanced map component ready");
    },

    watch: {
        customPoint: {
            deep: true,
            handler() {
                if (this.offline || this.customPoint) {
                    this.applyCustomPoints();
                }
            },
        },
    },

    methods: {
        normalizeCustomPoint(input) {
            if (!input) return [];
            return Array.isArray(input) ? input : [input];
        },

        buildMapDataFromCustomPoint(items) {
            const groups = new Map();

            items.forEach((item) => {
                const location =
                    item?.location ||
                    item?.coordinates ||
                    item?.geolocation ||
                    null;

                const lat = parseFloat(
                    location?.latitude ??
                        location?.lat ??
                        location?.LatLng?.lat,
                );

                const lng = parseFloat(
                    location?.longitude ??
                        location?.lng ??
                        location?.LatLng?.lng,
                );

                if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

                const cityId =
                    location?.id ?? item?.city_id ?? item?.cityId ?? null;
                const key = cityId ?? `${lat},${lng}`;

                const label =
                    location?.cityDesc ||
                    location?.city ||
                    item?.label ||
                    item?.name ||
                    "Unknown";

                if (!groups.has(key)) {
                    groups.set(key, {
                        id: key,
                        lat,
                        lng,
                        label,
                        total: 0,
                        city_id: cityId,
                    });
                }

                groups.get(key).total += 1;
            });

            return Array.from(groups.values());
        },

        applyCustomPoints() {
            const items = this.normalizeCustomPoint(this.customPoint);

            this.mapData = this.buildMapDataFromCustomPoint(items);

            this.mapMetadata = {};

            this.currentFilters = {
                data_type: this.initialDataType,
                ...this.initialFilters,
            };
        },

        handleDataUpdated(result) {
            this.mapData = result.data;
            this.mapMetadata = result.metadata;
            this.currentFilters = result.filters;
        },

        handleFiltersChanged(filters) {
            this.currentFilters = filters;
        },

        handleMarkerClick(marker) {
            this.selectedMarker = marker;
        },

        handleMapReady(map) {
            this.mapComponent = map;
        },

        fitMapToData() {
            if (this.$refs.mapComponent) {
                this.$refs.mapComponent.fitToMarkers();
            }
        },
    },
};
</script>
<template>
    <div class="flex flex-col lg:flex-row h-full relative">
        <!-- Floating Filter Toggle (Mobile) -->
        <div v-if="!offline && !customPoint" class="lg:hidden absolute top-4 left-4 z-[1000]">
            <button
                @click="showFilters = !showFilters"
                class="bg-white shadow-lg rounded-lg px-4 py-2 font-medium text-gray-700 hover:bg-gray-50"
            >
                Filters
            </button>
        </div>

        <!-- Floating Filter Panel -->
        <div
            v-if="!offline && !customPoint"
            :class="[
                'absolute top-16 left-4 z-[1000] w-80 max-h-[80vh] overflow-y-auto',
                'bg-white/95 backdrop-blur-sm rounded-xl shadow-2xl border border-gray-200',
                showFilters ? 'block' : 'hidden lg:block'
            ]"
        >
            <MapDataFilterPanel
                :initial-data-type="initialDataType"
                :initial-filters="initialFilters"
                @data-updated="handleDataUpdated"
                @filters-changed="handleFiltersChanged"
            />
        </div>

        <!-- Map fills entire container -->
        <div class="flex-1 h-full relative">
            <!-- Floating Header Info -->
            <div class="absolute top-4 right-4 z-[1000] bg-white/90 backdrop-blur-sm rounded-xl px-4 py-2 shadow-lg max-w-xs">
                <p class="text-sm text-gray-600">
                    <span class="font-medium text-pin-green">{{ mapData.length }}</span> locations
                    <span v-if="currentFilters.data_type" class="ml-1 text-gray-400">
                        • {{ currentFilters.data_type }}
                    </span>
                </p>
            </div>

            <!-- Fit to Data Button (Floating) -->
            <button
                @click="fitMapToData"
                class="absolute bottom-8 right-4 z-[1000] bg-white shadow-lg rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                :disabled="mapData.length === 0"
            >
                Fit to Data
            </button>

            <!-- Full Screen Map -->
            <LeafletMap
                ref="mapComponent"
                :map-data="mapData"
                :clustered="false"
                :showHeatmap="false"
                :center="[12.8797, 121.774]"
                :zoom="6"
                :data-type="currentFilters.data_type || initialDataType"
                height="100%"
                width="100%"
                @marker-click="handleMarkerClick"
                @map-ready="handleMapReady"
            />
        </div>
    </div>
</template>
