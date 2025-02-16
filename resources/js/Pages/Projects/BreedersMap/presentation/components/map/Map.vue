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
    LIcon,
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
import ViewIcon from "@/Components/Icons/ViewIcon.vue";
import FullscreenToggle from "@/Components/FullscreenToggle.vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import BaseClass from "@/Modules/core/domain/base/BaseClass";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import DataFiltrationFields
    from "@/Pages/Projects/BreedersMap/presentation/components/map/components/DataFiltrationFields.vue";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";

export default {
    components: {
        DataFiltrationFields,
        TransitionContainer,
        FullscreenToggle,
        ViewIcon,
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
        LIcon
    },
    props: {
        customPoint: {
            type: Object,
            required: false
        },
        offline: {
            type: Boolean,
            required: false,
            default: false
        },
        tableList: {
            type: Array,
            required: false,
            default: () => []
        },
        params: {
            type: Object,
            required: false,
            default: () => ({
                filter: null,
                search: null,
                is_exact: null
            })
        },
        model: {
            type: [BaseClass, Function],
            required: false,
        },
    },
    data() {
        return {
            dataFiltration: null,
            processingRequest: false,
            mapApi: null,
            icon: icon({
                iconUrl: "/img/logos/mappin.png",
                iconSize: [30, 40],
                iconAnchor: [15, 38],
            }),
            commodities: [],
            isHovered: false,
            tiles: null,
            map: null,
            placesSearched: [],
            //placesFiltered: [],
            dataFiltrationUrl: null,
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
        Breeder() {
            return Breeder
        },
        newData() {
            if (this.dataFiltration) {
                this.placesSearched = this.dataFiltration.raw_data;
                return this.placesSearched;
            }

            if (this.customPoint){
                this.placesSearched = this.customPoint;
                return this.customPoint;
            }

            return [];
        },

        Commodity() {
            return Commodity;
        },
        Permission() {
            return Permission;
        },
        sidebarVisible() {
            return this.mapApi ? this.mapApi.sidebarVisible : false;
        },
        province() {
            return regions;
        },
        mapOptions() {
            return this.mapApi ? this.mapApi.mapOptions : {};
        },
        processing() {
            return this.mapApi ? this.mapApi.processing : false;
        },
        dataPoints() {
            return this.mapApi ? this.mapApi.getDataPoint() : [];
        },
        canView() {
            return true; // return this.$page.props.permissions[Permission.VIEW];
        },
    },
    created() {
        this.initializeMap();
    },
    methods: {
        async initializeMap() {
            this.mapApi = new MapApiService(this.dataFiltrationUrl, this.model);
            //this.loadData();
        },
        async refreshData() {
            await this.mapApi.refresh();
            this.loadData();
        },
        async updateFilters(param, value) {

            if (this.mapApi && this.mapApi.processing) return;
            if (this.params.hasOwnProperty(param) && value)
            this.params[param] = value;
            await this.mapApi.updateParam(this.params);
            this.loadData();

            this.offlineSearch(value);
        },
        loadData() {
            this.commodities = this.dataPoints && this.dataPoints.length ? this.dataPoints : this.customPoint;
            if (this.commodities && this.commodities.length === 1) {
                this.selectPoint(this.commodities[0]);
            }
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
        isValidPoint(point) {
            return point && point.latitude && point.longitude;
        },
        selectPoint(point) {
            if (!this.$refs.map && !this.isValidPoint(point.location)) return;
            this.mapApi.selectPoint(point);
        },
        determinePointColor(value) {

            switch (value) {
                case 'Rice':
                    return '#005B41';
                case 'Corn':
                    return '#3B5998';
                case 'Coconut':
                    return '#FFA500';
                case 'Banana':
                    return '#FFD700';
                case 'Coffee':
                    return '#A52A2A';
                case 'Cassava':
                    return '#8B4513';
                case 'Abaca':
                    return '#FF0000';
                case 'Rubber':
                    return '#FF69B4';
                default:
                    return '#005B41';
            }
        },
        offlineSearch(search)
        {
            //filter every column
            this.placesSearched = this.newData.filter((point) => {
                return Object.keys(point).some((key) => {
                    return String(point[key]).toLowerCase().includes(search.toLowerCase());
                });
            });
        },
        baseURL() {
            return this.dataFiltrationUrl + `?filter_by_parent_column=${this.params.filter_by_parent_column}&filter_by_parent_id=${this.params.filter_by_parent_id}`;
        },
        getNestedValue(obj, path) {
            return path.split('.').reduce((acc, part) => acc && acc[part], obj);
        },
    },
    watch: {
        customPoint: {
            handler(newVal) {
                if (newVal) {
                    this.selectPoint(newVal);
                }
            },
            deep: true
        },
    }
};
</script>

<template>
    <div v-if="mapApi && canView" class="gap-1 justify-end hidden">
        <top-action-btn @click="refreshData" class="bg-add text-normal py-2" title="Export data">
            <template v-if="processing" #icon>
                <loader-icon class="h-auto sm:w-6 w-4" />
            </template>
            <span>Refresh</span>
        </top-action-btn>
        <top-action-btn class="bg-add text-normal" title="Export data">
            <template #icon>
                <export-icon class="h-auto sm:w-6 w-4" />
            </template>
            <span>Export</span>
        </top-action-btn>
        <top-action-btn class="bg-yellow-400 text-gray-900 text-normal" title="Share to your network">
            <template #icon>
                <share-icon class="h-auto sm:w-4 w-4" />
            </template>
            <span>Share</span>
        </top-action-btn>
    </div>
    <div v-if="mapApi && canView" class="flex flex-col max-h-fit gap-2 relative">
        <div class="relative gap-2">
            <template v-if="tableList && !offline">
                <data-filtration-fields
                    :tables="tableList"
                    @tableChange="dataFiltrationUrl = $event"
                    @dataRefreshed="dataFiltration = $event"
                    @processingRequest="processingRequest"
                    :params="params"
                />
            </template>
        </div>
        <div ref="mapContainer" id="bm-data-map" class="w-full flex gap-2 relative mt-1">
            <l-map
                ref="map"
                :use-global-leaflet="true"
                class="z-0 border rounded"
                style="height: 100%; min-height: 800px;"
                :zoom="mapApi.zoom"
                :center="mapApi.center"
                :maxZoom="mapApi.maxZoom"
                :minZoom="mapApi.minZoom"
                :max-bounds="[mapApi.maxBound.southwest, mapApi.maxBound.northeast]"
                :options="mapOptions"

                @update:zoom="updateZoom"
                @update:center="updateCenter"
            >
                <l-control-layers position="topright" :collapsed="true" />
                <l-geo-json
                    v-for="region in province"
                    :key="region.features[0].properties.ADM1_EN"
                    :name="region.features[0].properties.ADM1_EN"
                    layer-type="overlay"
                    :geojson="region"
                    :visible="false"
                    pane="overlayPane"
                />
                <l-tile-layer
                    v-for="tileProvider in tileProviders"
                    :key="tileProvider.name"
                    :name="tileProvider.name"
                    :visible="tileProvider.visible"
                    :url="tileProvider.url"
                    :attribution="tileProvider.attribution"
                    layer-type="base"
                />
                <l-marker v-if="mapApi.markerLatLng" :lat-lng="mapApi.markerLatLng" ref="marker" :icon="icon" @click="mapApi.sidebarVisible = !mapApi.sidebarVisible" />
                <template v-for="place in newData" :key="place.id" >
                    <l-circle-marker
                        v-if="isValidPoint(place.location)"
                        :lat-lng="[place.location.latitude, place.location.longitude]"
                        :opacity="1"
                        :radius="5"
                        :color="determinePointColor(place.name)"
                        :weight="1"
                        @click="selectPoint(place); mapApi.sidebarVisible = true;"
                    >
                        <l-tooltip :content="place.name" />
                    </l-circle-marker>
                </template>
                <l-control>
                    <div class="flex flex-col items-end">
                        <div class="flex flex-row gap-1 justify-between w-fit items-center p-2">
                            <top-action-btn @click="recenter" class="bg-add text-xs" title="Recenter Map">
                                <span>Recenter</span>
                            </top-action-btn>
                            <top-action-btn v-if="mapApi.selectedPlace" @click="deselectPoint" class="bg-add text-xs" title="Deselect Point">
                                <template #icon>
                                    <close-icon class="h-auto sm:w-6 w-4" />
                                </template>
                                <span>Deselect</span>
                            </top-action-btn>
                            <top-action-btn v-if="mapApi.selectedPlace" @click="mapApi.sidebarVisible = true" class="bg-add text-xs" title="Deselect Point">
                                <template #icon>
                                    <view-icon class="h-auto sm:w-4 w-3" />
                                </template>
                                <span>View&nbsp;Details</span>
                            </top-action-btn>
                            <FullscreenToggle :element="$refs.mapContainer" />
                        </div>
                        <info-sidebar :model="model" :point="mapApi.selectedPlace" :visible="sidebarVisible" @close="this.mapApi.sidebarVisible = false" />
                    </div>
                </l-control>
            </l-map>
        </div>
        <div class="hidden">
            <table v-if="newData">
                <thead>
                    <tr>
                        <template v-for="column in mapApi.model.getCardColumns()">
                            <th v-if="column.visible" :key="column.db_key">{{ column.title }}</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="point in newData" :key="point.id">
                        <template v-for="column in mapApi.model.getColumns()">
                            <td v-if="column.visible" :key="column.db_key">{{ getNestedValue(point, column.key) }}</td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-else class="flex flex-col max-h-fit gap-2" >
        You do not have permission to view this page
    </div>
</template>
