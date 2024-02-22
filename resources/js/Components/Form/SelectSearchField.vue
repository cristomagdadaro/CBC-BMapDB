<script>
import TextField from "@/Components/Form/TextField.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.js";
export default {
    components: {CloseIcon, TextField},
    emits: ['update:modelValue'],
    props:{
        modelValue: {
            type: [String, Number],
            default: null,
        },
        id: String,
        label: String,
        apiLink: String,
        options: {
            type: Array,
            required: false,
            default: () => [],
        },
        required: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            showDropdown: false,
            input: null,
            filteredOptions: [],
            api: null, // link to the api service where the options will be fetched
            allOptionsFromApiReceived: false, // flag to check if all options from the api have been received
        };
    },
    methods: {
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        },
        closeDropdown() {
            this.showDropdown = false;
        },
        filterOptions() {
            this.filteredOptions = this.options.filter((option) => option.label.toLowerCase().includes(this.input.toLowerCase()));
            if (this.filteredOptions.length === 0) {
                this.filteredOptions.push({label: 'No items found', value: null});
            }
        },
        selectOption(option) {
            this.$emit('update:modelValue', option.value);
            this.input = option.label;
            this.closeDropdown();
        },
        /**
         * Fetch options from the api
         * */
        async getOptionsFromApi(page = 1, search = null) {
            // if all options have been received, do not make any more requests
            if (this.allOptionsFromApiReceived) {
                return;
            }
            const response = await this.api.get({ search: search, per_page: 20, page: page });
            // if no options are returned, it means all options have been received
            if (response.data.data.length === 0) {
                this.allOptionsFromApiReceived = true;
            }
            const newOptions = response.data.data.map((option) => ({ value: option.id, label: option.name }));
            this.filteredOptions = [...this.filteredOptions, ...newOptions];
        },
        /**
         * Handle scroll event to fetch more options from the api
         * */
        async handleScroll(event) {
            const container = this.$refs.dropdownContainer;
            if (container.scrollHeight - container.scrollTop === container.clientHeight) {
                // get the next page
                const nextPage = Math.ceil(this.filteredOptions.length / 20) + 1;
                // prevent multiple requests
                if (this.api && this.api.processing) {
                    return;
                }
                await this.getOptionsFromApi(nextPage, this.input);
            }
        },
    },
    /**
     * Expose the focus method to the parent component to allow the parent to focus on the input field
     * */
    expose: ['focus'],
    mounted() {
        if (this.apiLink) {
            this.api = new ApiService(this.apiLink);
            this.getOptionsFromApi();
        }
    },
}

</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent" @mouseleave="closeDropdown()">
        <label :for="id" class="text-xs text-gray-600">{{ label }} <span v-if="required" class="text-red-500 font-bold">*</span></label>
        <div class="flex flex-col">
            <text-field
                :id="id"
                ref="input"
                :error="$attrs.error"
                :required="required"
                :show-clear="true"
                v-model="input"
                @focus="filterOptions()"
                @click="toggleDropdown()"
                @keyup="getOptionsFromApi(input)"
            />
            <div v-show="showDropdown" class="fixed inset-0 z-40" @click="closeDropdown()" />
            <div v-show="showDropdown" class="relative z-50">
                <div
                    ref="dropdownContainer"
                    @scroll="handleScroll"
                    class="fixed mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-md bg-white p-2 max-h-52 max-w-[20rem] overflow-x-auto z-50"
                >
                    <div v-show="filteredOptions.length !== 1" class="text-xs text-gray-200 pb-1 mb-1 select-none border-b border-gray-100">
                        Choose an option
                    </div>
                    <div v-for="option in filteredOptions" :key="option.value" @click="selectOption(option)" class="whitespace-nowrap hover:bg-gray-200 px-2 py-0.5 select-none rounded-sm overflow-ellipsis overflow-x-hidden">{{ option.label }}</div>
                    <div v-if="api && api.processing" class="text-center text-gray-300 whitespace-nowrap select-none">
                        fetching options...
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
