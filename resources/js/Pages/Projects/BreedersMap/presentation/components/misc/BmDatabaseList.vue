<script>
import {Link} from "@inertiajs/vue3";
import {CBCProjectsPublic} from "@/Pages/constants";

export default {
    name: "BmDatabaseList",
    methods: {
        CBCProjectsPublic() {
            return CBCProjectsPublic
        }
    },
    components: {Link}
}
</script>

<template>
    <p class="text-subtitle text-center text-dark-color">
        Dive deeper into our extensive databases
    </p>
    <p class="text-normal text-center text-dark-color">
        Stay tuned for more biotechnology related database coming soon
    </p>
    <div class="flex flex-row py-5 justify-between">
        <Link v-for="project in CBCProjectsPublic()" :href="route(project.value)" class="w-1/2 bg-white text-center p-10 hover:bg-cbc-dark-green hover:text-gray-100 hover:drop-shadow-lg active:scale-95 duration-200 uppercase text-subtitle">
            {{ project.label }}
        </Link>
    </div>
</template>

<style scoped>

</style>
