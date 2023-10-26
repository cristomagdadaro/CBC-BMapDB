<template>
    <div class="flex flex-col gap-0.5">
        <span class="text-xs text-gray-500">Search: </span>
        <div class="flex items-center gap-0.5 border-black bg-white rounded">
            <input class="border-0 py-1.5 rounded-l" type="text" v-model="search" id="dtSearch" @keyup="typing($event.target.value)" placeholder="Find it here" @keyup.capture.delete="searchBy" @keyup.capture.enter="searchBy" />
            <button class="bg-transparent rounded-r px-2 hover:bg-gray-300 active:bg-gray-400 h-full" @click="searchBy"><search-icon class="h-5 w-5" /></button>
        </div>
    </div>
</template>
<script>
import SearchIcon from "@/Components/Icons/SearchIcon.vue";
export default {
    components: {
        SearchIcon,
    },
    props: {
        // cannot use the value prop directly as a v-model, so instead we
        // created a search variable and set it to the value prop and return the search variable during emit
        value: {
            type: String,
            required: false,
        },
    },
    data(){
        return {
            search: this.value,
        }
    },
    methods: {
        searchBy(){
            this.$emit('searchString', this.search);
        },
        typing(val){
            this.search = val;
        }
    }
}
</script>
