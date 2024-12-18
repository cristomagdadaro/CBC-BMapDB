<template>
    <div class="relative max-h-[800px] flex flex-col w-2/5 bg-gray-100 rounded" :class="{ 'hidden': !visible }">
        <button @click="closeSidebar" class="absolute z-[999] top-0 w-fit right-3 p-1">
            <close-icon class="w-8 h-8 drop-shadow " />
        </button>
        <div v-if="point" class="drop-shadow overflow-y-auto overflow-x-hidden border">
            {{ point['breeder']}}
            <table>
                <tr v-for="(value, key) in model.getColumns()" :key="key">
                    <th class="text-right text-white whitespace-nowrap bg-[#006837] p-2 py-4">{{ value.title }}</th>
                    <td class="p-2 py-4">{{ point[value.key] }}</td>
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

