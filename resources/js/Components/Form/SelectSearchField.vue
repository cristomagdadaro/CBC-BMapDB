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
            displayedInput: null,
            filteredOptions: [],
            api: null,
            allOptionsFromApiReceived: false,
            last_page: 0,
            current_page: 0,
        };
    },
    methods: {
        setValueBasedOnModelValue() {
            if (this.modelValue) {
                const option = this.filteredOptions.find(option => option.value === this.modelValue);
                if (option) {
                    this.input = option.label;
                }
            }
        },
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        },
        closeDropdown() {
            this.input = null;
            this.showDropdown = false;
        },
        filterOptions() {
            this.filteredOptions = this.options.filter(option => option.label.toLowerCase().includes(this.input.toLowerCase()));
            if (this.filteredOptions.length === 0) {
                this.filteredOptions.push({ label: 'No items found', value: null });
            }
        },
        selectOption(option) {
            this.$emit('update:modelValue', option.value);
            this.displayedInput = option.label;
            this.closeDropdown();
        },
        fullnameOption(option) {
            return [option.fname, option.mname, option.lname, option.suffix].filter(part => part).join(" ");
        },
        async getOptionsFromApi(page = 1, search = null) {
            if (!search && this.allOptionsFromApiReceived) return;

            const response = await this.api.get({ search, per_page: 20, page });
            if (!response || !response.data || !response.data.data) return;

            this.last_page = response.data.meta.last_page;
            this.current_page = response.data.meta.current_page;
            this.allOptionsFromApiReceived = response.data.data.length === 0;

            if (!search && this.filteredOptions.length > 0 && response.data.data.length === 0) return;

            this.filteredOptions = response.data.data.map(option => ({
                value: option.id,
                label: option.name || option.title || option.label || option.value || this.fullnameOption(option)
            }));
        },
        async handleScroll(event) {
            const container = this.$refs.dropdownContainer;
            if (container.scrollHeight - container.scrollTop === container.clientHeight) {
                const nextPage = Math.ceil(this.filteredOptions.length / 20) + 1;
                if (this.api && this.api.processing && this.current_page >= this.last_page) return;
                await this.getOptionsFromApi(nextPage, this.input);
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
            this.getOptionsFromApi(1, null);
        }
    },
    watch: {
        modelValue() {
            this.getOptionsFromApi(1, this.modelValue);
        },
        filteredOptions() {
            this.setValueBasedOnModelValue();
        }
    },
};
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
                v-model="displayedInput"
                @click="toggleDropdown()"
                @keydown="filterOptions()"
                @keydown.enter="getOptionsFromApi(1, input)"
            />
            <div v-show="showDropdown" class="fixed inset-0 z-40" @click="clearInput()" />
            <div v-show="showDropdown" class="relative z-50">
                <div v-if="api"
                     ref="dropdownContainer"
                     @scroll="handleScroll"
                     class="fixed mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-md bg-white p-2 max-h-52 max-w-[20rem] overflow-x-auto z-50"
                >
                    <div v-show="filteredOptions.length !== 1" class="text-xs text-gray-200 pb-1 mb-1 select-none border-b border-gray-100">
                        <p v-if="api.processing">fetching more options</p>
                        <p v-else>Choose an option</p>
                    </div>
                    <div v-if="filteredOptions.length" v-for="option in filteredOptions" :key="option.value" @click="selectOption(option)" class="whitespace-nowrap hover:bg-gray-200 px-2 py-0.5 select-none rounded-sm overflow-ellipsis overflow-x-hidden">{{ option.label }}</div>
                    <div v-else>Not found</div>
                    <div v-if="api.processing" class="text-center text-gray-300 whitespace-nowrap select-none">
                        fetching options...
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
