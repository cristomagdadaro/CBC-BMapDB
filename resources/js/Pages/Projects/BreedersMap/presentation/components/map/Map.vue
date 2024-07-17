<script>
import {
    LMap,
    LTileLayer,
    LMarker,
    LCircleMarker,
    LPopup,
    LControlLayers,
    LGeoJson,
    LTooltip
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
import ApiService from "@/Modules/core/infrastructure/ApiService.js";

export default {
    components: {
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
        LTooltip
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
            type: Object,
            required: false,
        }
    },
    data() {
        return {
            api: null,
            icon: icon({
                iconUrl: "/public/img/logo_no_bg.png",
                iconSize: [25, 41],
                iconAnchor: [12, 41],
            }),
            showListOfPlaces: false,
            sidebarVisible: false,
            commodities: [],
            isHovered: false,
            tiles: null,
            map: null,
            zoom: 5.9,
            minZoom: 5.9,
            maxZoom: 15,
            center: [12.296167, 122.763835],
            maxBound: {
                southwest: [4.284376, 116.521894],
                northeast: [21.327897, 126.895418]
            },
            markerLatLng: null,
            selectedPlace: null,
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
        province() {
            return regions;
        },
        mapOptions() {
            return {
                zoomControl: true,
                attributionControl: false,
                maxBoundsViscosity: 1,
                zoomAnimation: true,
                fadeAnimation: true,
                markerZoomAnimation: true,
                zoomAnimationThreshold: 4,
                doubleClickZoom: true,
                keyboard: true,
                closePopupOnClick: false,
                dragging: true,
                touchZoom: true,
                scrollWheelZoom: true,
                tap: true
            };
        },
    },
    mounted() {
        this.commodities = this.$page.props.commodities || this.customPoint;
        this.placesFiltered = this.commodities;
        this.placesSearched = this.placesFiltered;
        this.initializeApi();
    },
    methods: {
        selectPoint(point) {
            if (!this.$refs.map) return;
            this.markerLatLng = [point.latitude, point.longitude];
            this.selectedPlace = point;
            this.updateCenter(this.markerLatLng);
            this.updateZoom(8);
            this.sidebarVisible = true; // open the sidebar on point selection
        },
        updateCenter(center) {
            this.center = center;
        },
        updateZoom(zoom) {
            this.zoom = zoom;
        },
        isPointSelected(point) {
            return this.markerLatLng && this.markerLatLng[0] === point.latitude && this.markerLatLng[1] === point.longitude;
        },
        deselectPoint() {
            this.markerLatLng = null;
            this.selectedPlace = null;
            this.sidebarVisible = false;
            this.updateZoom(this.minZoom);
        },
        initializeApi() {
            if (this.baseUrl && !this.api) {
                this.api = new ApiService(this.baseUrl);
                this.getPlacesFromAPI();
            }
        },
        getPlacesFromAPI(search = null) {
            if (this.api || search) {
                this.api.get({
                    search: search,
                }, this.model).then(response => {
                    this.commodities = response.data.data;
                    this.placesFiltered = this.commodities;
                    this.placesSearched = this.placesFiltered;
                }).catch(error => {
                    console.log(error);
                });
            }
        }
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
    <div class="flex gap-1 justify-end">
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
    <div class="flex flex-col max-h-fit gap-2">
        <div class="relative gap-2">
            <search-box
                :value="selectedPlace ? selectedPlace.city : ''"
                :options="placesFiltered"
                :label="selectedPlace ? selectedPlace.city : 'Select a place'"
                @searchString="getPlacesFromAPI($event.target.value)"
                @input="getPlacesFromAPI($event.target.value)"
                @focusin="showListOfPlaces = true"
            />

            <div v-if="showListOfPlaces" class="absolute mt-1 rounded border-2 z-[999] bg-gray-100 shadow flex-col flex gap-1 overflow-y-auto max-h-96 p-2">
                <div
                    v-if="placesSearched.length"
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
        <div class="w-full flex gap-2">
            <l-map
                ref="map"
                :use-global-leaflet="true"
                class="z-0 border rounded"
                style="height: 800px"
                :zoom="zoom"
                :center="center"
                :maxZoom="maxZoom"
                :minZoom="minZoom"
                :max-bounds="[maxBound.southwest, maxBound.northeast]"
                :options="mapOptions"
                @update:center="updateCenter"
                @update:zoom="updateZoom"
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
                <l-marker v-if="markerLatLng" :lat-lng="markerLatLng" ref="marker" />
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
<!--                    <l-popup>
                        <h1>{{ place.city }}</h1>
                    </l-popup>-->
                </l-circle-marker>
            </l-map>
            <info-sidebar :point="selectedPlace" :visible="sidebarVisible" @close="sidebarVisible = false" />
        </div>
    </div>
</template>
