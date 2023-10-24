<template>
    <div class="flex flex-col gap-0.5">
        <span class="text-xs text-gray-500">Search: </span>
        <div class="flex items-center gap-0.5 border-black bg-white rounded">
            <input class="border-0 py-1.5 rounded-l" type="text" id="dtSearch" v-model="searchString" placeholder="Find it here" @keyup.capture.enter="$emit('searchString', searchString)" />
            <button class="bg-transparent rounded-r px-2 hover:bg-gray-300 active:bg-gray-400 h-full" @click="$emit('searchString', searchString )"><search-icon class="h-5 w-5" /></button>
        </div>
    </div>
</template>
<script>
import SearchIcon from "@/Components/Icons/SearchIcon.vue";
import {ref} from "vue";
export default {
    components: {
        SearchIcon,
    },
    data(){
        return {
            searchString: ref(''),
        }
    },
}
</script>
