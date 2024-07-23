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
        setValueBasedOnModelValue() {
            if (this.modelValue) {
                const option = this.filteredOptions.find((option) => option.value === this.modelValue);
                if (option) {
                    this.input = option.label;
                }
            }
        },
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
            this.getOptionsFromApi(1, null);
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
        fullnameOption(option) {
            return [option.fname, option.mname, option.lname, option.suffix]
                .filter(part => part)
                .join(" ");
        },
        /**
         * Fetch options from the api
         * */
        async getOptionsFromApi(page = 1, search = null) {

            // if all options have been received, do not make any more requests
            if (!search && this.allOptionsFromApiReceived) {
                return;
            }
            const response = await this.api.get({ search: search, per_page: 20, page: page });
            // if no options are returned, it means all options have been received
            this.allOptionsFromApiReceived = response.data.data.length === 0;
            // if the search input is empty, it means we are fetching the first page of options and when the next page has no data, go back to the previous page
            if (!search && this.filteredOptions.length > 0 && response.data.data.length === 0) {
                return;
            }

            this.filteredOptions = response.data.data.map((option) => ({value: option.id, label: option.name || option.title || option.label || option.value || this.fullnameOption(option) }));
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
            this.getOptionsFromApi(1, null);
        }
    },
    watch: {
        modelValue() {
            this.getOptionsFromApi(1, null);
        },
        filteredOptions() {
            this.setValueBasedOnModelValue();
        }
    },
}

</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent" @mouseleave="closeDropdown()">
        <div class="flex flex-col">
            <text-field
                :id="id"
                ref="input"
                :label="label"
                :error="$attrs.error"
                :required="required"
                :show-clear="true"
                v-model="input"
                @focus="filterOptions()"
                @click="toggleDropdown()"
                @keyup="getOptionsFromApi(1, input)"
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
                    <div v-if="filteredOptions.length" v-for="option in filteredOptions" :key="option.value" @click="selectOption(option)" class="whitespace-nowrap hover:bg-gray-200 px-2 py-0.5 select-none rounded-sm overflow-ellipsis overflow-x-hidden">{{ option.label }}</div>
                    <div v-else>Not found</div>
                    <div v-if="api && api.processing" class="text-center text-gray-300 whitespace-nowrap select-none">
                        fetching options...
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
