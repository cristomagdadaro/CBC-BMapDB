<script>
import TextField from "@/Components/Form/TextField.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.ts";

export default {
    components: { CloseIcon, TextField },
    emits: ['update:modelValue'],
    props: {
        modelValue: {
            type: [String, Number],
            default: null,
        },
        id: String,
        label: String,
        apiLink: String,
        required: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            showDropdown: false,
            input: null,
            displayedInput: null,
            filteredOptions: [],
            api: null,
            allOptionsFromApiReceived: false,
            last_page: 0,
            current_page: 0,
            next_page: 0,
            options: [],
        };
    },
    methods: {
        setValueBasedOnModelValue() {
            if (this.modelValue && this.filteredOptions && this.filteredOptions.length) {
                const option = this.filteredOptions.find(option => option.value === this.modelValue);
                this.selectOption(option);
            }
        },
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        },
        closeDropdown() {
            this.input = null;
            this.showDropdown = false;
        },
        filterOptions(search) {
            if (!this.options || !this.options.length) {
                this.getOptionsFromApi();
            }

            if (!search) {
                this.filteredOptions = this.options;
                return;
            }

            this.filteredOptions = this.options.filter(option => option.label.toLowerCase().includes(search.toString().toLowerCase()) || option.value.toString().toLowerCase().includes(search.toString().toLowerCase()));

            if (!this.filteredOptions || !this.filteredOptions.length) {
                this.getOptionsFromApi(null, search);
            }

            if (this.filteredOptions.length === 0) {
                this.filteredOptions.push({ label: 'No items found', value: null });
            }
        },
        selectOption(option) {
            if (!option) return;
            this.$emit('update:modelValue', option.value);
            this.displayedInput = option.label;
        },
        fullnameOption(option) {
            return [option.fname, option.mname, option.lname, option.suffix].filter(part => part).join(" ");
        },
        async getOptionsFromApi(page = 1, search = null) {
            if (!search && this.allOptionsFromApiReceived) return;

            let response = null;

            if (search) {
                this.input = search;
                response = await this.api.get({search: search, per_page: 20, page: page});
            }
            else if (this.modelValue) {
                response = await this.api.get({search: this.modelValue, filter: 'id', per_page: 20, page: page});
            }
            else
                response = await this.api.get({ per_page: 20, page: page });

            if (!response || !response.data || !response.data.data) return;

            this.last_page = response.data.meta.last_page;
            this.current_page = response.data.meta.current_page;

            this.next_page = response.data.meta.current_page + 1;

            if (this.current_page >= this.last_page) {
                this.next_page = this.last_page;
                this.allOptionsFromApiReceived = true;
            } else {
                this.allOptionsFromApiReceived = false;
            }

            if (!search && this.filteredOptions && this.filteredOptions.length > 0 && response.data.data.length === 0) return;

            this.filteredOptions = [...this.filteredOptions,...response.data.data.map(option => ({
                value: option.id,
                label: option.name || option.title || option.label || option.value || this.fullnameOption(option)
            }))];
        },
        async handleScroll(event) {
            event.preventDefault();
            const container = this.$refs.dropdownContainer;
            if (container.scrollHeight - container.scrollTop === container.clientHeight) {
                await this.getOptionsFromApi(this.next_page, null);
            }
        },
        clearInput() {
            this.input = null;
            this.getOptionsFromApi(1, null);
        },
    },
    expose: ['focus'],
    mounted() {
        if (this.apiLink) {
            this.api = new ApiService(this.apiLink);
            //this.getOptionsFromApi();
        }
    },
    watch: {
        modelValue(oldVal, newVal) {
            if (oldVal === newVal) return;

            this.filterOptions(newVal);
            if (this.filteredOptions && this.filteredOptions.length)
                this.setValueBasedOnModelValue();
            else
                this.displayedInput = null;
        },
        filteredOptions() {
            this.setValueBasedOnModelValue();
        },
        displayedInput(newVal) {
            if (!newVal) {
                this.$emit('update:modelValue', null);
            }
        },
    },
    computed: {
        retrievingOptions() {
            return this.api && this.api.processing;
        },
    },
};
</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent" @mouseleave="closeDropdown()">
        <div class="flex flex-col">
            <text-field
                :id="id"
                ref="input"
                :label="label + (retrievingOptions ? ` ( loading options )`: '')"
                :error="$attrs.error"
                :required="required"
                :show-clear="true"
                v-model="displayedInput"
                @click="toggleDropdown(); getOptionsFromApi(1, $event.target.value)"
                @keydown="getOptionsFromApi(1, $event.target.value)"
                @keydow.enter="getOptionsFromApi(1, $event.target.value)"
            />
            <div v-show="showDropdown" class="fixed inset-0 z-40d" @click="closeDropdown" />
            <div v-show="showDropdown" class="relative z-50">
                <div v-if="api"
                     ref="dropdownContainer"
                     @scroll="handleScroll"
                     class="fixed mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-md bg-white p-2 max-h-52 max-w-[20rem] overflow-x-auto z-50"
                >
                    <div v-show="filteredOptions && filteredOptions.length !== 1" class="text-xs text-gray-200 pb-1 mb-1 select-none border-b border-gray-100">
                        <p v-if="api.processing">fetching more options</p>
                        <p v-else>Choose an option</p>
                    </div>
                    <div v-if="filteredOptions && filteredOptions.length" v-for="option in filteredOptions" :key="option.value" @click="selectOption(option); closeDropdown();" class="whitespace-nowrap hover:bg-gray-200 px-2 py-0.5 select-none rounded-sm overflow-ellipsis overflow-x-hidden">{{ option.label }}</div>
                    <div v-else>Not found</div>
                    <div v-if="api.processing" class="text-center text-gray-300 whitespace-nowrap select-none">
                        fetching options...
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
