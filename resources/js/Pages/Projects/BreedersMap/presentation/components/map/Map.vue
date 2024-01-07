<template>
    <div class="flex gap-1 justify-end">
        <top-action-btn
            class="bg-add text-xs"
            title="Add new data">
            <template #icon>
                <export-icon class="h-auto sm:w-6 w-4" />
            </template>
            <span v-show="true">Export</span>
        </top-action-btn>
        <top-action-btn
            class="bg-yellow-400 text-gray-900 text-xs"
            title="Add new data">
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
                :label="selectedPlace ? selectedPlace.place_name : 'Select a place'"
                @input="filterPlaces($event.target.value)"
            />
            <div class="rounded flex-col flex gap-1 overflow-y-auto max-h-96">

                <template v-for="point in placesSearched">
                    <div @click="selectPoint([point.lat, point.lon])" :class="markerLatLng[0]===point.lat&&markerLatLng[1]===point.lon?'bg-gray-400':'bg-white'" class="flex flex-col gap-1 border p-1 rounded hover:bg-gray-200 leading-1 duration-200 select-none">
                        <h1 class="font-medium leading-5">{{ point.place_name }}</h1>
                    </div>
                </template>
            </div>
        </div>
        <!--    <div id="map" class="h-screen"></div>-->
        <l-map
            :use-global-leaflet="true"
            :zoom-animation="true"
            :fadeAnimation="true"
            :markerZoomAnimation="true"
            class="z-0 border rounded"
            style="height: 800px"
            :zoom="zoom"
            :center="center"
            :maxZoom="maxZoom"
            :minZoom="minZoom"
            :max-bounds="[maxBound.southwest, maxBound.northeast]"
            :max-bounds-viscosity="-5"
        >

            <l-geo-json
                v-for="region in province"
                :name="region.features[0].properties.ADM1_EN"
                :layer-type="'overlay'"
                :geojson="region"
                :visible="true"
                :pane="'overlayPane'"
                :style="{
                color: '#ff7800',
                weight: 5,
                opacity: 0.65,
                fillColor: 'red',
                fillOpacity: 0.8
            }" />

            <l-control-layers position="topright" :collapsed="false" />

            <l-tile-layer
                v-for="tileProvider in tileProviders"
                :key="tileProvider.name"
                :name="tileProvider.name"
                :visible="tileProvider.visible"
                :url="tileProvider.url"
                :attribution="tileProvider.attribution"
                layer-type="base"/>

            <l-marker v-if="markerLatLng" :lat-lng="markerLatLng">
                <l-popup>
                    <h2>Crop Biotechnology Center</h2>
                </l-popup>
            </l-marker>


            <l-circle-marker
                v-for="place in placesFiltered"
                :key="place.place_id"
                :lat-lng="[place.lat, place.lon]"
                :opacity="0.8"
                color="#3DA5B4"
                :weight="1"
            >
                <l-tooltip
                    :content="place.place_name"
                >
                    <div>
                        <table>
                            <tr
                                v-for="(value, key) in place"
                                :key="key"
                            >
                                <th>{{ key }}</th>
                                <td>{{ value }}</td>
                            </tr>
                        </table>
                    </div>
                </l-tooltip>
                <l-popup>
                    <div>
                        <table>
                            <tr
                                v-for="(value, key) in place"
                                :key="key"
                            >
                                <th>{{ key }}</th>
                                <td>{{ value }}</td>
                            </tr>
                        </table>
                    </div>
                </l-popup>
            </l-circle-marker>
        </l-map>
    </div>
</template>

<script>
import region1 from '@/Pages/Projects/BreedersMap/components/geojsons/geoJson.js';
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
export default {
    computed: {
        province() {
            return regions;
        }
    },
    components: {
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
        this.placesSearched = this.placesFiltered;
    },
    methods: {
        selectPoint(point){
            this.markerLatLng = point;
            this.center = point;
            this.zoom = 10;
            this.selectedPlace = this.placesFiltered.find(place => place.lat === point[0] && place.lon === point[1]);
        },
        filterPlaces(str) {
            this.placesSearched = this.placesFiltered.filter(place =>
                place.place_name.toLowerCase().includes(str.toLowerCase())
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
            isHovered: false,
            tiles: null,
            map: null,
            zoom: 5.8,
            minZoom: 5.8,
            maxZoom: 15,
            center: [12.296167, 122.763835],
            maxBound: {
                southwest: [4.284376, 116.521894],
                northeast: [21.327897, 126.895418]
            },
            markerLatLng: [0,0],
            selectedPlace: null,
            placesSearched: [],
            placesFiltered: [
                {
                    "place_id": 1,
                    "place_name": "Benguet State University",
                    "lat": 16.403,
                    "lon": 120.596,
                    "place_type": "University",
                    "place_address": "La Trinidad, Benguet",
                    "place_contact": "09123456789",
                    "place_website": "https://bsu.edu.ph"
                },
                {
                    "place_id": 2,
                    "place_name": "University of the Philippines Diliman",
                    "lat": 14.6539,
                    "lon": 121.0664,
                    "place_type": "University",
                    "place_address": "Diliman, Quezon City",
                    "place_contact": "09123456789",
                    "place_website": "https://upd.edu.ph"
                },
                {
                    "place_id": 3,
                    "place_name": "Polytechnic University of the Philippines",
                    "lat": 14.6078,
                    "lon": 121.0014,
                    "place_type": "University",
                    "place_address": "Sta. Mesa, Manila",
                    "place_contact": "09123456789",
                    "place_website": "https://www.pup.edu.ph"
                },
                {
                    "place_id": 4,
                    "place_name": "Mindanao State University",
                    "lat": 7.0718,
                    "lon": 125.6094,
                    "place_type": "University",
                    "place_address": "Marawi City",
                    "place_contact": "09123456789",
                    "place_website": "https://msumain.edu.ph"
                },
                {
                    "place_id": 5,
                    "place_name": "Central Luzon State University",
                    "lat": 15.4861,
                    "lon": 120.9673,
                    "place_type": "University",
                    "place_address": "Science City of Muñoz, Nueva Ecija",
                    "place_contact": "09123456789",
                    "place_website": "https://clsu.edu.ph"
                },
                {
                    "place_id": 6,
                    "place_name": "Visayas State University",
                    "lat": 11.1217,
                    "lon": 123.9898,
                    "place_type": "University",
                    "place_address": "Baybay City, Leyte",
                    "place_contact": "09123456789",
                    "place_website": "https://www.vsu.edu.ph"
                },
                {
                    "place_id": 7,
                    "place_name": "University of the Philippines Los Baños",
                    "lat": 14.1676,
                    "lon": 121.2425,
                    "place_type": "University",
                    "place_address": "Los Baños, Laguna",
                    "place_contact": "09123456789",
                    "place_website": "https://uplb.edu.ph"
                },
                {
                    "place_id": 8,
                    "place_name": "Bicol University",
                    "lat": 13.6224,
                    "lon": 123.1848,
                    "place_type": "University",
                    "place_address": "Legazpi City, Albay",
                    "place_contact": "09123456789",
                    "place_website": "https://bicol-u.edu.ph"
                },
                {
                    "place_id": 9,
                    "place_name": "Western Mindanao State University",
                    "lat": 6.9152,
                    "lon": 122.0736,
                    "place_type": "University",
                    "place_address": "Zamboanga City",
                    "place_contact": "09123456789",
                    "place_website": "https://wmsu.edu.ph"
                },
                {
                    "place_id": 10,
                    "place_name": "Cebu Normal University",
                    "lat": 10.3083,
                    "lon": 123.8933,
                    "place_type": "University",
                    "place_address": "Cebu City",
                    "place_contact": "09123456789",
                    "place_website": "https://cnu.edu.ph"
                },
                {
                    "place_id": 11,
                    "place_name": "Caraga State University",
                    "lat": 8.9536,
                    "lon": 125.5234,
                    "place_type": "University",
                    "place_address": "Butuan City",
                    "place_contact": "09123456789",
                    "place_website": "https://carsu.edu.ph"
                },
                {
                    "place_id": 12,
                    "place_name": "University of Southeastern Philippines",
                    "lat": 7.0897,
                    "lon": 125.6128,
                    "place_type": "University",
                    "place_address": "Davao City",
                    "place_contact": "09123456789",
                    "place_website": "https://usep.edu.ph"
                },
                {
                    "place_id": 13,
                    "place_name": "University of the Philippines Visayas",
                    "lat": 10.6969,
                    "lon": 122.5644,
                    "place_type": "University",
                    "place_address": "Miagao, Iloilo",
                    "place_contact": "09123456789",
                    "place_website": "https://upv.edu.ph"
                },
                {
                    "place_id": 14,
                    "place_name": "University of the Philippines Mindanao",
                    "lat": 7.1907,
                    "lon": 125.4553,
                    "place_type": "University",
                    "place_address": "Mintal, Davao City",
                    "place_contact": "09123456789",
                    "place_website": "https://upmin.edu.ph"
                },
                {
                    "place_id": 15,
                    "place_name": "University of the Philippines Baguio",
                    "lat": 16.4131,
                    "lon": 120.5998,
                    "place_type": "University",
                    "place_address": "Baguio City",
                    "place_contact": "09123456789",
                    "place_website": "https://upb.edu.ph"
                },
                {
                    "place_id": 16,
                    "place_name": "University of the Philippines Cebu",
                    "lat": 10.3157,
                    "lon": 123.8854,
                    "place_type": "University",
                    "place_address": "Cebu City",
                    "place_contact": "09123456789",
                    "place_website": "https://upcebu.edu.ph"
                },
                {
                    "place_id": 17,
                    "place_name": "University of the Philippines Open University",
                    "lat": 14.1559,
                    "lon": 121.2423,
                    "place_type": "University",
                    "place_address": "Los Baños, Laguna",
                    "place_contact": "09123456789",
                    "place_website": "https://upou.edu.ph"
                },
                {
                    "place_id": 18,
                    "place_name": "Central Mindanao University",
                    "lat": 7.6271,
                    "lon": 125.0867,
                    "place_type": "University",
                    "place_address": "Musuan, Bukidnon",
                    "place_contact": "09123456789",
                    "place_website": "https://www.cmu.edu.ph"
                },
                {
                    "place_id": 19,
                    "place_name": "Iloilo Science and Technology University",
                    "lat": 10.7255,
                    "lon": 122.5643,
                    "place_type": "University",
                    "place_address": "Iloilo City",
                    "place_contact": "09123456789",
                    "place_website": "https://www.istu.edu.ph"
                },
                {
                    "place_id": 20,
                    "place_name": "University of Southeastern Philippines",
                    "lat": 7.0897,
                    "lon": 125.6110,
                    "place_type": "University",
                    "place_address": "Davao City",
                    "place_contact": "09123456789",
                    "place_website": "https://www.usep.edu.ph"
                },
                {
                    "place_id": 21,
                    "place_name": "Isabela State University",
                    "lat": 17.3200,
                    "lon": 121.5015,
                    "place_type": "University",
                    "place_address": "Echague, Isabela",
                    "place_contact": "09123456789",
                    "place_website": "https://isu.edu.ph"
                },
                {
                    "place_id": 22,
                    "place_name": "Cagayan State University",
                    "lat": 17.6436,
                    "lon": 121.7490,
                    "place_type": "University",
                    "place_address": "Tuguegarao City, Cagayan",
                    "place_contact": "09123456789",
                    "place_website": "https://www.csu.edu.ph"
                },
            ],
            tileProviders: [
                {
                    name: 'CARTO',
                    visible: true,
                    attribution: '<span class="bg-transparent">&copy; <a class="bg-transparent" href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a class="bg-transparent" href="https://carto.com/attributions">CARTO</a></span>',
                    url: 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
                },
                {
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
                },
            ],
        };
    }
}
</script>
