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
            default: "commodities",
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
    <div class="flex flex-col lg:flex-row gap-4 h-full p-2 md:p-3">
        <!-- Mobile Filter Toggle -->
        <div v-if="!offline && !customPoint" class="lg:hidden">
            <button
                @click="showFilters = !showFilters"
                class="btn-primary w-full py-2"
            >
                Filters
            </button>
        </div>

        <!-- Filter Panel -->
        <div
            v-if="!offline && !customPoint"
            :class="[
                'lg:w-80 flex-shrink-0',
                showFilters ? 'block' : 'hidden lg:block',
            ]"
        >
            <MapDataFilterPanel
                :initial-data-type="initialDataType"
                :initial-filters="initialFilters"
                @data-updated="handleDataUpdated"
                @filters-changed="handleFiltersChanged"
            />
        </div>

        <!-- Map Section -->
        <div class="flex-1 flex flex-col gap-4 min-h-[70vh]">
            <!-- Header -->
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                >
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Geographic Distribution
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Showing
                            <span class="font-medium text-pin-green">
                                {{ mapData.length }}
                            </span>
                            locations

                            <span v-if="currentFilters.data_type" class="ml-1">
                                •
                                <span class="badge">
                                    {{ currentFilters.data_type }}
                                </span>

                                <span v-if="currentFilters.filter_by">
                                    filtered by
                                    <span class="badge">
                                        {{ currentFilters.filter_by }}
                                    </span>
                                </span>
                            </span>
                        </p>
                    </div>

                    <button
                        @click="fitMapToData"
                        class="btn-primary text-sm px-4 py-2 w-full sm:w-auto"
                        :disabled="mapData.length === 0"
                    >
                        Fit to Data
                    </button>
                </div>
            </div>

            <!-- Map -->
            <div
                class="flex-1 bg-white rounded-xl shadow-card overflow-hidden min-h-[400px]"
            >
                <LeafletMap
                    ref="mapComponent"
                    :map-data="mapData"
                    :clustered="false"
                    :showHeatmap="false"
                    :center="[12.8797, 121.774]"
                    :zoom="6"
                    :data-type="currentFilters.data_type || initialDataType"
                    height="100%"
                    @marker-click="handleMarkerClick"
                    @map-ready="handleMapReady"
                />
            </div>
        </div>
    </div>
</template>
