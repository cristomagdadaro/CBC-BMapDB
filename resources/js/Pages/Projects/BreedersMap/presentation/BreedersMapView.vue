<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
    components: {AppLayout},
    data() {
        return {
            id: this.$page.props.id,
            data: null,
        }
    },
    methods: {
        getDataFromAPI() {
            axios.get(route('api.breeders.show', this.id)).then((response) => {
                this.data = response.data;
            });
        }
    },
    mounted() {
        this.getDataFromAPI();
    }
}
</script>

<template>
    <head title="Breeder's Map View" />
    <app-layout>
        {{ data }}
    </app-layout>
</template>

<style scoped>

</style>
