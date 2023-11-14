<template>
    <l-map
        class="z-0"
        style="height: 500px"
        :zoom="zoom"
        :center="center"
        :maxZoom="maxZoom"
        :minZoom="minZoom"
    >
        <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
        <l-marker :lat-lng="markerLatLng">
            <l-popup>
                <h2>Crop Biotechnology Center</h2>
            </l-popup>
        </l-marker>
        <l-circle-marker
            v-for="place in placesFiltered"
            :key="place.place_id"
            :lat-lng="[place.lat, place.lon]"
            opacity="0.8"
            color="#3DA5B4"
            weight="1"
        >
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
</template>

<script>
import {LMap, LTileLayer, LMarker, LCircleMarker, LPopup} from "@vue-leaflet/vue-leaflet";
import 'leaflet/dist/leaflet.css';
export default {
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LCircleMarker,
        LPopup,
    },
    data () {
        return {
            //url: "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png",
            //url: 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_nolabels/{z}/{x}/{y}{r}.png',
            //url: 'https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}',
            url: 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
            zoom: 6,
            minZoom: 6,
            maxZoom: 6,
            center: [15.669225749333105, 120.89068526776725],
            markerLatLng: [15.669225749333105, 120.89068526776725],
            //url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            subdomains: 'abcd',
            //attribution: '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
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
            ]

        };
    }
}
</script>
