<template>
    <div class="flex flex-col gap-0.5">
        <span class="text-xs text-gray-500">Search: </span>
        <div class="flex flex-row items-center justify-between gap-0.5 bg-white rounded focus-within:ring-1 border-gray-200 border">
            <input autocomplete="off" class="border-0 py-2 w-full rounded-l active:ring-0 focus:ring-0" type="text" v-model="search" id="dtSearch" @keyup="typing($event.target.value)" :placeholder="placeholder" @keyup.capture.delete="!search.length?searchBy():null" @keyup.capture.enter="searchBy" />
            <button v-if="search" class="p-2 rounded-r hover:border-0 active:scale-90 duration-300 hover:bg-gray-300 active:bg-gray-400 h-full" @click="clearSearch"><close-icon class="h-5 w-5"/> </button>
            <button v-else class="p-2 rounded-r hover:border-0 active:scale-90 duration-300 hover:bg-gray-300 active:bg-gray-400 h-full" @click="searchBy"><search-icon class="h-5 w-5 text-gray-600" /></button>
        </div>
    </div>
</template>
<script>
import SearchIcon from "@/Components/Icons/SearchIcon.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
export default {
    components: {
        CloseIcon,
        SearchIcon,
    },
    props: {
        value: {
            type: String,
            required: false,
        },
        placeholder: {
            type: String,
            default: 'Find it here...',
        }
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
        },
        clearSearch(){
            this.search = '';
            this.searchBy();
        }
    }
}
</script>
