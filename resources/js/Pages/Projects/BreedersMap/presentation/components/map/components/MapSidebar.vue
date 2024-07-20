<template>
    <div class="relative max-h-[800px] flex flex-col w-2/5 bg-gray-100 rounded" :class="{ 'hidden': !visible }">
        <button @click="closeSidebar" class="absolute z-[999] top-0 w-fit right-3 p-1">
            <close-icon class="w-8 h-8 text-red-700 drop-shadow " />
        </button>
        <div v-if="point" class="drop-shadow overflow-y-auto overflow-x-hidden border">
            <table>
                <tr v-for="(value, key) in point" :key="key">
                    <th class="text-right text-white bg-[#006837] p-2 py-4">{{ formatName(key) }}</th>
                    <td class="p-2 py-4">{{ value }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import CloseIcon from "@/Components/Icons/CloseIcon.vue";

export default {
    components: {CloseIcon},
    props: {
        point: Object,
        visible: Boolean
    },
    methods: {
        closeSidebar() {
            this.$emit('close');
        },
        formatName(name) {
            return name.replace(/_/g, ' ').toUpperCase();
        }
    }
}
</script>
