<template>
    <div class="flex gap-1 justify-end">
        <top-action-btn
            class="bg-add text-xs"
            title="Export data">
            <template #icon>
                <export-icon class="h-auto sm:w-6 w-4" />
            </template>
            <span v-show="true">Export</span>
        </top-action-btn>
        <top-action-btn
            class="bg-yellow-400 text-gray-900 text-xs"
            title="Share to your network">
            <template #icon>
                <share-icon class="h-auto sm:w-4 w-4" />
            </template>
            <span v-show="true">Share</span>
        </top-action-btn>
    </div>
    <div class="flex sm:flex-row flex-col max-h-fit gap-1">
        <div class="flex flex-col gap-2">
            <search-box
                :value="selectedPlace"
                :options="placesFiltered"
                :label="selectedPlace ? selectedPlace.city : 'Select a place'"
                @searchString="filterPlaces($event.target.value)"
                @input="filterPlaces($event.target.value)"
            />
            <div class="rounded flex-col flex gap-1 overflow-y-auto max-h-96">
                <template v-for="point in placesSearched">
                    <div @click="selectPoint(point)" :class="markerLatLng[0]===point.latitude&&markerLatLng[1]===point.latitude?'bg-gray-400':'bg-white'" class="flex flex-row items-center gap-1 border p-1 rounded hover:bg-gray-200 leading-1 duration-200 select-none">
                        <checkbox/>
                        <h1 class="font-medium leading-5">{{ point.city }}</h1>
                    </div>
                </template>
            </div>
        </div>
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
            :options="{
        zoomControl: true,
        attributionControl: false,
        maxBoundsViscosity: 1,
        zoomAnimation: true,
        fadeAnimation: true,
        markerZoomAnimation: true,
        zoomAnimationThreshold: 4,
        doubleClickZoom: false,
        keyboard: false,
        closePopupOnClick: false,
        dragging: false,
        touchZoom: false,
        scrollWheelZoom: false,
        tap: false,
      }"
        >
            <l-geo-json
                v-for="region in province"
                :name="region.features[0].properties.ADM1_EN"
                layer-type="overlay"
                :geojson="region"
                :visible="false"
                pane="overlayPane"
            />
            <l-control-layers position="topright" :collapsed="true"  />
            <l-tile-layer
                v-for="tileProvider in tileProviders"
                :key="tileProvider.name"
                :name="tileProvider.name"
                :visible="tileProvider.visible"
                :url="tileProvider.url"
                :attribution="tileProvider.attribution"
                layer-type="base"
            />
            <l-marker v-if="markerLatLng" :lat-lng="markerLatLng" ref="marker">
                <!-- Remove the l-popup here -->
            </l-marker>
            <l-circle-marker
                v-for="place in placesFiltered"
                :key="place.id"
                :lat-lng="[place.latitude, place.longitude]"
                :opacity="0.8"
                color="#3DA5B4"
                :weight="1"
                @click="selectPoint(place)"
            >
                <!-- Remove the l-tooltip and l-popup here -->
            </l-circle-marker>
        </l-map>
    </div>
    <!-- Include the sidebar component -->
    <info-sidebar :point="selectedPlace" :visible="sidebarVisible" @close="sidebarVisible = false" />
</template>

<script>
import region1 from '@/Pages/Projects/BreedersMap/components/geojsons/geoJson.js';
import InfoSidebar from './components/MapSidebar.vue';
import {
    LMap,
    LTileLayer,
    LMarker,
    LCircleMarker,
    LPopup,
    LControlLayers,
    LFeatureGroup, LTooltip, LRectangle, LGeoJson
} from "@vue-leaflet/vue-leaflet";
import 'leaflet/dist/leaflet.css';
import regions from "@/Pages/Projects/BreedersMap/components/geojsons/geoJson.js";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import TextField from "@/Components/Form/TextField.vue";
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import TopActionBtn from "@/Components/CRCMDatatable/Components/TopActionBtn.vue";
import AddIcon from "@/Components/Icons/AddIcon.vue";
import ExportIcon from "@/Components/Icons/ExportIcon.vue";
import ShareIcon from "@/Components/Icons/ShareIcon.vue";
import Checkbox from "@/Components/Checkbox.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
export default {
    computed: {
        province() {
            return regions;
        }
    },
    components: {
        InfoSidebar,
        SearchBy,
        Checkbox,
        ShareIcon,
        ExportIcon,
        AddIcon,
        TopActionBtn,
        SearchBox,
        TextField,
        SelectSearchField,
        LGeoJson,
        LRectangle,
        LTooltip,
        LFeatureGroup,
        LControlLayers,
        LMap,
        LTileLayer,
        LMarker,
        LCircleMarker,
        LPopup,
    },
    mounted() {
        this.commodities = this.$page.props.commodities;
        this.placesFiltered = this.commodities;
        this.placesSearched = this.placesFiltered;
    },
    methods: {
        // capitalize the first letter of each word and remove underscores
        formatName(name) {
            return name
                .split('_')
                .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
            // return name.replace(/_/g,'');

        },
        selectPoint(point) {
            // Set the marker's position
            this.markerLatLng = point;

            // Set the map's center and zoom level
            this.center = point;
            this.zoom = 10;

            // Find the selected place based on the current point
            this.selectedPlace = this.placesFiltered.find(
                (place) => place.latitude === point[0] && place.longitude === point[1]
            );

            // Fly to the selected point with animation
            if (this.$refs.map) {
                this.$refs.map.mapObject.flyTo(point, 10, {
                    animate: true,
                    duration: 1.5,
                });
            }
            this.sidebarVisible = true;
            // Increment the index for the next point
            this.currentIndex = (this.currentIndex + 1) % this.placesFiltered.length;
        },
        filterPlaces(str) {
            this.placesSearched = this.placesFiltered.filter(place =>
                place.city.toLowerCase().includes(str.toLowerCase())
            );
        },
        highlightFeature(event) {
            console.log(event);
            if (!this.isHovered) {
                this.isHovered = true;
                const layer = event.target;
                layer.setStyle({
                    fillColor: 'yellow',
                    fillOpacity: 1
                });
            }
        },
        resetHighlight(event) {
            this.isHovered = false;
            const layer = event.target;
            layer.setStyle({
                fillColor: layer.feature.properties.color || 'red',
                fillOpacity: 0.7,
                color: 'black',
                weight: 1
            });
        }
    },
    data () {
        return {
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
            markerLatLng: [0,0],
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
                    url:'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_nolabels/{z}/{x}/{y}{r}.png'
                },
                {
                    name: 'CartoDB DarkMatter',
                    visible: false,
                    url:'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
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
                /*{
                    name: 'OpenStreetMap',
                    visible: false,
                    attribution:
                        '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
                    url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                },
                {
                    name: 'OpenTopoMap',
                    visible: false,
                    url: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
                    attribution:
                        'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
                },*/
            ],
        };
    }
}
</script>
