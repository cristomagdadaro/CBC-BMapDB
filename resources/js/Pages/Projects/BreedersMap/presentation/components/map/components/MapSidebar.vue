<template>
    <div class="relative max-h-[800px] flex flex-col w-2/5 bg-gray-100 rounded" :class="{ 'hidden': !visible }">
        <button @click="closeSidebar" class="absolute z-[999] top-0 w-fit right-3 p-1">
            <close-icon class="w-8 h-8 drop-shadow " />
        </button>
        <div v-if="point" class="drop-shadow overflow-y-auto overflow-x-hidden border">
            <table>
                <tr colspan="2" class="bg-cbc-yellow-green text-white text-center uppercase">
                    <th colspan="2" class="p-2">Breeder Details</th>
                </tr>
                <tr v-for="(value, key) in Breeder.getColumns()" :key="key">
                    <th v-if="point['breeder'][value.key]" class="text-right text-white whitespace-nowrap bg-cbc-dark-green p-2">{{ value.title }}</th>
                    <td v-if="point['breeder'][value.key]" class="p-2">{{ point['breeder'][value.key] }}</td>
                </tr>
                <tr colspan="2" class="bg-cbc-yellow-green text-white text-center uppercase">
                    <th colspan="2" class="p-2">Commodity Details</th>
                </tr>
                <tr v-for="(value, key) in model.getColumns()" :key="key">
                    <th v-if="point[value.key]" class="text-right text-white whitespace-nowrap bg-cbc-dark-green p-2">{{ value.title }}</th>
                    <td v-if="point[value.key]" class="p-2">{{ point[value.key] }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";

export default {
    computed: {
        Breeder() {
            return Breeder
        }
    },
    components: {CloseIcon},
    props: {
        point: Object,
        visible: Boolean,
        model: [Object, Function]
    },
    methods: {
        closeSidebar() {
            this.$emit('close');
        },
        formatName(name) {
            return name;
        }
    }
}
</script>

