`<script setup>
import {Head} from "@inertiajs/vue3";
import {onMounted} from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import CreateBreederForm from "@/Pages/Projects/BreedersMap/CreateBreederForm.vue";
import EditBreederForm from "@/Pages/Projects/BreedersMap/EditBreederForm.vue";
import Tab from "@/Components/Tab/Tab.vue";
let loc = [
    [12.8797, 121.7740], // Location 1
    [13.3586, 122.7494], // Location 2
    [10.3157, 123.8854], // Location 3
    [13.4050, 122.5954], // Location 4
    [10.7202, 122.5621], // Location 5
    [14.6760, 121.0437], // Location 6
    [9.8284, 118.7386],  // Location 7
    [7.1907, 124.5362],  // Location 8
    [11.2448, 124.9647], // Location 9
    [15.1858, 120.5565], // Location 10
];

onMounted(() => {
    const map = L.map('map').setView([15.822900, 120.886400], 9);

    const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 15,
        minZoom: 8,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    function PlotLocations(locations){
        // Loop through the locations and add markers to the map
        for (let i = 0; i < locations.length; i++) {
            const location = locations[i];
            L.marker(location).addTo(map)
                .bindPopup('Location ' + (i + 1)); // You can customize the popup content here
        }
    }
    PlotLocations(loc);

    // console log the information contained in the popup when clicked
    /*map.on('popupopen', function(e) {
         alert(e.popup.getContent());
    });*/

    map.openPopup('<b class="text-xs font-medium">You can use Tailwind classes to design the leaflet library.</b>',[15.1858, 120.5565],{
        closeButton: true,
        keepInView: true,
        className: 'bg-cbc-dark-green p-2',
    })
});

</script>
<template>
    <Head title="Breeder's Map" />
    <app-layout>
        <div class="min-h-screen bg-gray-500 sm:p-4 p-1">
            <Tab :tabs="tabs" v-if="$page.props.auth.user">
                <template #tab1>
                    <CRCMDatatable
                        :base-url="baseUrl"
                        :base-model="baseModel"
                        :add-form="addForm"
                        :edit-form="editForm"
                    />
                </template>
                <template #tab2>
                    Tab 2
                </template>
                <template #tab3>
                    Tab 3
                </template>
            </Tab>
            <p v-else>Please login to view the data</p>

        </div>
        <div class="w-full sm:p-5 p-0">
            <div class="flex flex-wrap gap-2">
                <div id="map" class="border shadow-md"></div>
            </div>
        </div>
    </app-layout>

</template>
<style scoped>
#map {
    @apply sm:w-[50%] w-full h-screen z-0;
}
</style>
<script>
import User from "@/Modules/core/domain/User.js";
import Role from "@/Modules/core/domain/Role.js";
import Permission from "@/Modules/core/domain/Permission.js";

import Breeder from "@/Modules/core/domain/Breeder.js";
import CreateBreederForm from "@/Pages/Projects/BreedersMap/CreateBreederForm.vue";
import EditBreederForm from "@/Pages/Projects/BreedersMap/EditBreederForm.vue";
import {markRaw} from "vue";

export default {
    data(){
        return {
            baseUrl: route('api.breeders.index'),
            baseModel: Breeder,
            addForm: markRaw(CreateBreederForm),
            editForm: markRaw(EditBreederForm),
            tabs: [
                {
                    name: "tab1",
                    label: "Breeders",
                    active: true,
                },
                {
                    name: "tab2",
                    label: "Products",
                    active: false,
                },
                {
                    name: "tab3",
                    label: "Services",
                    active: false,
                },
            ]
        }
    }
}

// You can now use the 'locations' array in your Leaflet map.




/*var circle = L.circle([15.822900, 120.896500], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 500
}).addTo(map);

marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();
circle.bindPopup("I am a circle.");
var popup = L.popup()
    .setLatLng([15.832900, 120.896500])
    .setContent("I am a standalone popup.")
    .openOn(map);


function onMapClick(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("You clicked the map at " + e.latlng.toString())
        .openOn(map);
}

map.on('click', onMapClick);*/
</script>
