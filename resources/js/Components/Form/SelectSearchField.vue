<script>
import TextField from "@/Components/Form/TextField.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.ts";
import BaseResponse from "@/Modules/core/domain/base/BaseResponse";
import SelectField from "@/Components/Form/SelectField.vue";
import BaseClass from "@/Modules/core/domain/base/BaseClass";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

export default {
    components: {TransitionContainer, SelectField, CloseIcon, TextField },
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
        placeholder: {
            type: String,
            default: null,
        },
        disabled: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            api: null,
            fetchedResponse: null,
            formattedOptions: [],
            filteredOptions: [],
            showDropdown: false,
            displayedInput: null,
            autoFocus: false,
        };
    },
    methods: {
        toggleDropdown() {
            if (this.disabled) return;
            this.showDropdown = !this.showDropdown;
            this.autoFocus = true;
        },
        async getOptionsFromApi(search = null, page = 1) {
            this.fetchedResponse = await this.api.get({
                ...(search ? { search } : {}),
                per_page: 20,
                page,
            });
            if (this.fetchedResponse instanceof BaseResponse){
                if (this.fetchedResponse.data && this.fetchedResponse.data.length)
                this.formattedOptions = this.fetchedResponse.data.map(option => ({
                    value: option.id,
                    label: option.name || option.title || option.label || option.value || (new BaseClass(option)).getFullName ,
                }));

                this.filteredOptions = this.formattedOptions;

                if (search) {
                    this.filteredOptions.forEach(option => {
                        if (option.value === search) {
                            this.selectOption(option);
                        }
                    });
                }
            }
        },
        selectOption(option) {
            this.$emit('update:modelValue', option.value);
            this.displayedInput = option.label;
            this.closeDropdown();
        },
        closeDropdown() {
            this.showDropdown = false;
        },
        debounceApiCall(search) {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.getOptionsFromApi(search);
            }, 300);
        },
        handleClickOutside(event) {
            if (this.$el && !this.$el.contains(event.target)) {
                this.closeDropdown();
            }
        }
    },
    expose: ['focus'],
    mounted() {
        if (this.apiLink) {
            this.api = new ApiService(this.apiLink);
            this.getOptionsFromApi(this.modelValue);
        }

        document.addEventListener("click", this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener("click", this.handleClickOutside);
    },
    watch: {
        modelValue(newVal) {
            this.getOptionsFromApi(newVal);
        },
        displayedInput(newVal){
            if (!newVal)
            {
                this.$emit('update:modelValue', null);
                this.displayedInput = null;
            }
        },
        'api.processing'(newVal) {
            if (!newVal && this.autoFocus) {
                this.$nextTick(() => {
                    this.$refs?.textInput.focus();
                });
            }
        }
    },
     computed: {
        processing() {
            return this.api?.processing;
        },
         dynamicLabel() {
            return this.processing ? `${this.label} (loading)` : this.label;
         }
     }
};
</script>

<template>
    <div class="relative flex flex-col border-0 p-0 bg-transparent">
        <div class="flex flex-col w-full">
            <text-field
                :id="id"
                ref="textInput"
                :label="dynamicLabel"
                :error="$attrs.error"
                :required="required"
                :show-clear="!disabled"
                :disabled="disabled || processing"
                v-model="displayedInput"
                :placeholder="placeholder"
                @focusin="toggleDropdown()"
                @click="toggleDropdown()"
                @keydown="debounceApiCall($event.target.value)"
            />
            <div v-show="showDropdown && !api.processing" class="relative z-[999]" @focusout="closeDropdown()">
                <div v-if="api"
                     class="absolute left-0 mt-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-md bg-white p-2 max-h-52 max-w-[20rem] overflow-x-auto z-50"
                >
                <div v-show="filteredOptions.length !== 1" class="text-xs text-gray-200 pb-1 mb-1 select-none border-b border-gray-100">
                        <p v-if="api.processing">fetching more options</p>
                        <p v-else>Choose an option</p>
                    </div>
                    <div v-if="filteredOptions.length" v-for="option in filteredOptions" :key="option.value" @click="selectOption(option)" class="whitespace-nowrap hover:bg-gray-200 px-2 py-0.5 select-none rounded-sm overflow-ellipsis overflow-x-hidden">{{ option.label }}</div>
                    <div v-else-if="displayedInput">Not found</div>
                    <div v-else>Type a word</div>
                    <div v-if="api.processing" class="text-center text-gray-300 whitespace-nowrap select-none">
                        fetching options...
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
