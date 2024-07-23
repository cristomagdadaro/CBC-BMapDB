<script>
import {
    LMap,
    LTileLayer,
    LMarker,
    LCircleMarker,
    LPopup,
    LControlLayers,
    LGeoJson,
    LTooltip,
    LControl,
} from "@vue-leaflet/vue-leaflet";
import 'leaflet/dist/leaflet.css';
import { icon } from 'leaflet';
import regions from "@/Pages/Projects/BreedersMap/components/geojsons/geoJson.js";
import InfoSidebar from './components/MapSidebar.vue';
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import TopActionBtn from "@/Components/CRCMDatatable/Components/TopActionBtn.vue";
import ExportIcon from "@/Components/Icons/ExportIcon.vue";
import ShareIcon from "@/Components/Icons/ShareIcon.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import MapApiService from "@/Pages/Projects/BreedersMap/presentation/components/map/infrastructure/MapApiService.js";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import {Permission} from "@/Pages/constants.ts";

export default {
    components: {
        SearchBy,
        LoaderIcon,
        CloseIcon,
        InfoSidebar,
        SearchBox,
        TopActionBtn,
        ExportIcon,
        ShareIcon,
        LMap,
        LTileLayer,
        LMarker,
        LCircleMarker,
        LPopup,
        LControlLayers,
        LGeoJson,
        LTooltip,
        LControl,
    },
    props: {
        customPoint: {
            type: Object,
            required: false
        },
        baseUrl: {
            type: String,
            required: false,
        },
        params: {
            type: Object,
            required: false,
            default: () => {
                return {
                    filter: null,
                    search: null,
                    is_exact: null
                }
            }
        },
        model: {
            type: [Object, Function],
            required: false,
        }
    },
    data() {
        return {
            mapApi: null,
            icon: icon({
                iconUrl: "/public/img/logo_no_bg.png",
                iconSize: [25, 41],
                iconAnchor: [12, 41],
            }),
            showListOfPlaces: false,
            commodities: [],
            isHovered: false,
            tiles: null,
            map: null,
            placesSearched: [],
            placesFiltered: [],
            tileProviders: [
                {
                    name: 'CartoDB Voyager',
                    visible: true,
                    url: 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png',
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                },
                {
                    name: 'CartoDB VoyagerNoLabels',
                    visible: false,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    url: 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_nolabels/{z}/{x}/{y}{r}.png'
                },
                {
                    name: 'CartoDB DarkMatter',
                    visible: false,
                    url: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                },
                {
                    name: 'CartoDB DarkMatterNoLabels',
                    visible: false,
                    url: 'https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png',
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                },
                {
                    name: 'Esri WorldGrayCanvas',
                    visible: false,
                    url: 'https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}',
                    attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ',
                }
            ],
        };
    },
    computed: {
        Permission() {
            return Permission;
        },
        sidebarVisible() {
            if (this.mapApi)
                return this.mapApi.sidebarVisible;
        },
        province() {
            return regions;
        },
        mapOptions() {
            if (this.mapApi)
                return this.mapApi.mapOptions;
        },
        processing() {
            if (this.mapApi)
                return this.mapApi.processing;
        },
        dataPoints() {
            if (this.mapApi)
                return this.mapApi.getDataPoint();
        },
        canView() {
            return this.$page.props.permissions[Permission.VIEW];
        },
    },
    mounted() {
        this.initializeMap();
    },
    methods: {
        async initializeMap() {
            this.mapApi = new MapApiService(this.baseUrl, this.baseModel);
            await this.mapApi.init();
            this.loadData();
        },
        async refreshData() {
            await this.mapApi.refresh();
            this.loadData();
        },
        async updateFilters(param, value) {
            // prevent multiple request when typing
            if (this.mapApi && this.mapApi.processing) {
                return;
            }
            this.params[param] = value;
            await this.mapApi.updateParam(this.params);
            this.loadData();
        },
        loadData() {
            this.commodities = this.dataPoints || this.customPoint;
            this.placesFiltered = this.commodities;
            this.placesSearched = this.placesFiltered;
        },
        selectPoint(point) {
            if (!this.$refs.map) return;
            this.mapApi.selectPoint(point);
        },
        updateCenter(center) {
            this.mapApi.updateCenter(center);
        },
        updateZoom(zoom) {
            this.mapApi.updateZoom(zoom);
        },
        isPointSelected(point) {
            return this.mapApi.isPointSelected(point);
        },
        deselectPoint() {
            this.mapApi.deselectPoint();
        },
        recenter() {
            this.mapApi.recenter();
        },
        selectedPoint() {
            return this.mapApi.selectedPlace;
        },
    },
    watch: {
        customPoint: {
            handler: function (newVal, oldVal) {
                if (newVal) {
                    this.selectPoint(newVal);
                }
            },
            deep: true
        }
    }
};
</script>


<template>
<div v-if="mapApi && canView" class="flex gap-1 justify-end">
        <top-action-btn @click="refreshData" class="bg-add text-xs" title="Export data">
            <template v-if="processing" #icon>
                <loader-icon class="h-auto sm:w-6 w-4" />
            </template>
            <span>Refresh</span>
        </top-action-btn>
        <top-action-btn class="bg-add text-xs" title="Export data">
            <template #icon>
                <export-icon class="h-auto sm:w-6 w-4" />
            </template>
            <span>Export</span>
        </top-action-btn>
        <top-action-btn class="bg-yellow-400 text-gray-900 text-xs" title="Share to your network">
            <template #icon>
                <share-icon class="h-auto sm:w-4 w-4" />
            </template>
            <span>Share</span>
        </top-action-btn>
    </div>
    <div class="flex flex-col max-h-fit gap-2" v-if="mapApi && canView">
        <div class="relative gap-2">
            <div class="w-full flex gap-1">
                <search-box
                    :value="mapApi.selectedPlace ? mapApi.selectedPlace.city : ''"
                    :options="placesFiltered"
                    :label="mapApi.selectedPlace ? mapApi.selectedPlace.city : 'Select a place'"
                    @searchString="updateFilters('search', $event)"
                    @input="updateFilters('search', $event.target.value)"
                    @focusin="showListOfPlaces = true"
                    class="w-full"
                />
                <search-by :value="mapApi.request.params.filter"
                           :is-exact="mapApi.request.params.is_exact"
                           :options="mapApi.columns"
                           @isExact="mapApi.isExactFilter({ is_exact: $event })"
                           @searchBy="mapApi.filterByColumn({ column: $event })"
                />
            </div>

            <div v-if="showListOfPlaces" class="absolute mt-1 rounded border-2 z-[999] bg-gray-100 shadow flex-col flex gap-1 overflow-y-auto max-h-96 p-2">
                <div
                    v-if="placesSearched && placesSearched.length"
                    v-for="point in placesSearched"
                    :key="point.id"
                    @click="selectPoint(point)"
                    :class="{'bg-gray-400': isPointSelected(point),'bg-white': !isPointSelected(point)}"
                    class="flex flex-row items-center gap-1 border p-1 py-2 rounded hover:bg-gray-200 leading-1 duration-200 select-none"
                >
                    <h1 class="font-medium leading-5 px-1 w-full flex items-center justify-between">
                        {{ point.city }}
                        <close-icon v-if="isPointSelected(point)" @click="deselectPoint(); showListOfPlaces = false" class="h-4 w-4 hover:scale-110 duration-200" @click.stop="markerLatLng = null" />
                    </h1>
                </div>
                <div
                    v-else
                    class="flex flex-row items-center gap-1 p-1 py-2 rounded hover:bg-gray-200 leading-1 duration-200 select-none"
                >
                    <h1 class="font-medium leading-5 px-1 w-full flex items-center justify-between">
                        No data available
                    </h1>
                </div>
            </div>
        </div>
        <div class="w-full flex gap-2 relative">
            <div v-if="processing" class="flex gap-1 absolute top-0 left-0 min-w-full min-h-full">
                <span class="whitespace-nowrap">Fetching data</span>
            </div>
            <l-map
                ref="map"
                :use-global-leaflet="true"
                class="z-0 border rounded"
                style="height: 800px"
                :zoom="mapApi.zoom"
                :center="mapApi.center"
                :maxZoom="mapApi.maxZoom"
                :minZoom="mapApi.minZoom"
                :max-bounds="[mapApi.maxBound.southwest, mapApi.maxBound.northeast]"
                :options="mapOptions"

                @update:zoom="updateZoom"
                @update:center="updateCenter"
                @click="showListOfPlaces = false"
            >
                <l-geo-json
                    v-for="region in province"
                    :key="region.features[0].properties.ADM1_EN"
                    :name="region.features[0].properties.ADM1_EN"
                    layer-type="overlay"
                    :geojson="region"
                    :visible="false"
                    pane="overlayPane"
                />
                <l-control-layers position="topright" :collapsed="true" />
                <l-tile-layer
                    v-for="tileProvider in tileProviders"
                    :key="tileProvider.name"
                    :name="tileProvider.name"
                    :visible="tileProvider.visible"
                    :url="tileProvider.url"
                    :attribution="tileProvider.attribution"
                    layer-type="base"
                />
                <l-marker v-if="mapApi.markerLatLng" :lat-lng="mapApi.markerLatLng" ref="marker" />
                <l-circle-marker
                    v-for="place in placesFiltered"
                    :key="place.id"
                    :lat-lng="[place.latitude, place.longitude]"
                    :opacity="0.8"
                    color="#3DA5B4"
                    :weight="1"
                    @click="selectPoint(place)"
                >
                    <l-tooltip :content="place.city" />
                </l-circle-marker>
                <l-control>
                    <top-action-btn @click="recenter" class="bg-add text-xs" title="Recenter Map">
                        <span>Recenter</span>
                    </top-action-btn>
                    <top-action-btn v-if="mapApi.selectedPlace" @click="deselectPoint" class="bg-add text-xs" title="Deselect Point">
                        <template #icon>
                            <close-icon class="h-auto sm:w-6 w-4" />
                        </template>
                        <span>Deselect</span>
                    </top-action-btn>
                </l-control>
            </l-map>
            <info-sidebar :point="mapApi.selectedPlace" :visible="sidebarVisible" @close="this.mapApi.sidebarVisible = false" />
        </div>
    </div>
    <div v-else class="flex flex-col max-h-fit gap-2" >
        You do not have permission to view this page
    </div>
</template>
