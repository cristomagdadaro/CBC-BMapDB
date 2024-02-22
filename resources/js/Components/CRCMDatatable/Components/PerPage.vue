<template>
    <div class="flex items-center gap-1 text-sm">
        Show
        <custom-dropdown :value="value" :withAllOption="false" :options="perPage" @selectedChange="$emit('changePerPage', $event)">
            <template #icon>
                <caret-down  class="h-4 w-4" />
            </template>
        </custom-dropdown>
        entries
    </div>
</template>
<script>
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import CaretDown from "@/Components/Icons/CaretDown.vue";

export default {
    components: {CaretDown, CustomDropdown},
    props: {
        value: String,
    },
    data(){
        return {
            perPage: [
                {label: '10', name: '10', selected: true},
                {label: '25', name: '25', selected: false},
                {label: '50', name: '50', selected: false},
                {label: '100', name: '100', selected: false},
            ],
        }
    },
}
</script>
