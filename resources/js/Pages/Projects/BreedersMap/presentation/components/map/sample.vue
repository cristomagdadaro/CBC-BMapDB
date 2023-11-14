/* eslint-disable */
<template>
    <l-map
        :zoom="zoom"
        :center="center"
    >
        <l-tile-layer
            :url="mapStyle"
            attribution="&copy; CARTO"
        />

        <l-circle-marker
            v-for="place in placesFiltered"
            :key="place.place_id"
            :lat-lng="[place.lat, place.lon]"
            opacity="0.6"
            color="#3DA5B4"
            weight="2"
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
import {
    LMap,
    LTileLayer,
    LCircleMarker,
    LPopup
} from "vue2-leaflet";
import 'leaflet/dist/leaflet.css';
import placeOutputData from "../../assets/maps/bcdestablishments.json"
import { Icon } from 'leaflet';

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

export default {
    components: {
        LMap,
        LTileLayer,
        LCircleMarker,
        LPopup
    },
    data() {
        return {
            mapStyle: "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png",
            zoom: 14,
            center: [10.6713, 122.9511],
            places: placeOutputData,
            skippedKeys: [
                'licence',
                'osm_id',
                'osm_type',
                'place_rank',
                'boundingbox',
                'display_name',
                'address',
                'addresstype',
                'importance'
            ],

        };
    },
    computed: {
        placesFiltered: function () {
            const keysToSkip = this.skippedKeys;
            const filteredPairs = this.places.map(place => {
                const filteredPair = {};
                for (const key in place) {
                    if (!keysToSkip.includes(key)) {
                        filteredPair[key] = place[key];
                    }
                }
                return filteredPair;
            });
            return filteredPairs;
        }
    },
    methods: {
        log(a) {
            console.log(a);
        },
        isKeyToSkip(key) {
            const keysToSkip = this.skippedKeys;
            return !keysToSkip.includes(key);
        }
    },
};
</script>
