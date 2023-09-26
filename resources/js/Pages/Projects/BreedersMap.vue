<script setup>
import PageLayout from "@/Layouts/PageLayout.vue";
import {Head} from "@inertiajs/vue3";
import {onMounted} from "vue";
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
    <page-layout>
        <div class="w-full sm:p-5 p-0">
            <div class="flex flex-wrap gap-2">
                <div class="max-w-[400px]">
                    <h1 class="font-bold sm:text-lg text-md">Breeder's Map</h1>
                    <p class="text-justify sm:text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eum harum ipsa iure libero maxime natus nisi, odio perferendis quae, qui reprehenderit voluptate! Adipisci aliquid animi aspernatur blanditiis consequatur cum, cupiditate dignissimos distinctio dolores dolorum earum eius eos eum facilis hic illo illum incidunt ipsum iusto laborum libero maxime minus, nemo nobis officia pariatur quis recusandae reiciendis repellendus sequi sunt suscipit veniam vero! A accusamus accusantium architecto culpa cumque dicta dolores, doloribus, dolorum ducimus ea eaque enim error est fuga ipsam itaque laudantium molestias mollitia neque nesciunt nostrum porro qui quibusdam quo quod ratione reiciendis reprehenderit sed sunt tenetur unde ut.</p>
                </div>
                <div id="map" class="border shadow-md"></div>
            </div>
        </div>
    </page-layout>

</template>
<style scoped>
#map {
    @apply sm:w-[50%] w-full h-screen z-0;
}
</style>
<script>


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
