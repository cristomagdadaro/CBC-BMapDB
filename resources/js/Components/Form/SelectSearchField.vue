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
        },
        title: {
            type: String,
            default: null,
        },
        perPage: {
            type: Number,
            default: 10,
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
            currentPage: 1,
            hasMoreData: true,
            isLoadingMore: false,
            currentSearch: '',
            debounceTimeout: null,
            selectedOption: null,
        };
    },
    methods: {
        toggleDropdown() {
            if (this.disabled) return;

            if (!this.showDropdown) {
                this.showDropdown = true;
                this.autoFocus = true;

                // Load initial data if not already loaded
                if (this.formattedOptions.length === 0) {
                    this.resetPagination();
                    this.getOptionsFromApi();
                }
            } else {
                this.closeDropdown();
            }
        },

        async getOptionsFromApi(search = null, page = 1, append = false) {
            if (!this.api) return;

            try {
                // Prevent multiple simultaneous requests
                if (this.isLoadingMore && append) return;

                this.isLoadingMore = append;

                const params = {
                    per_page: this.perPage,
                    page,
                    ...(search ? { search } : {}),
                };

                this.fetchedResponse = await this.api.get(params);

                if (this.fetchedResponse instanceof BaseResponse && this.fetchedResponse.data) {
                    const newOptions = this.fetchedResponse.data.map(option => ({
                        value: option.id,
                        label: option.name || option.title || option.label || option.value || (new BaseClass(option)).getFullName,
                    }));

                    if (append) {
                        this.formattedOptions = [...this.formattedOptions, ...newOptions];
                    } else {
                        this.formattedOptions = newOptions;
                    }

                    this.filteredOptions = this.formattedOptions;

                    // Check if there's more data
                    this.hasMoreData = newOptions.length === this.perPage;

                    // Auto-select if searching for specific value
                    if (search && !append) {
                        const foundOption = this.filteredOptions.find(option =>
                            option.value == search || option.label.toLowerCase().includes(search.toLowerCase())
                        );
                        if (foundOption && this.modelValue == foundOption.value) {
                            this.selectedOption = foundOption;
                            this.displayedInput = foundOption.label;
                        }
                    }
                }
            } catch (error) {
                console.error('Error fetching options:', error);
            } finally {
                this.isLoadingMore = false;
            }
        },

        async loadMoreOptions() {
            if (!this.hasMoreData || this.isLoadingMore || !this.api) return;

            this.currentPage++;
            await this.getOptionsFromApi(this.currentSearch, this.currentPage, true);
        },

        selectOption(option) {
            this.selectedOption = option;
            this.$emit('update:modelValue', option.value);
            this.displayedInput = option.label;
            this.closeDropdown();
        },

        closeDropdown() {
            this.showDropdown = false;
            this.autoFocus = false;
        },

        resetPagination() {
            this.currentPage = 1;
            this.hasMoreData = true;
            this.formattedOptions = [];
            this.filteredOptions = [];
        },

        debounceApiCall(searchValue) {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.handleSearch(searchValue);
            }, 300);
        },

        handleSearch(searchValue) {
            this.currentSearch = searchValue;
            this.resetPagination();
            this.getOptionsFromApi(searchValue);
        },

        handleDropdownScroll(event) {
            const { scrollTop, scrollHeight, clientHeight } = event.target;
            const threshold = 50; // pixels from bottom

            if (scrollHeight - scrollTop - clientHeight < threshold) {
                this.loadMoreOptions();
            }
        },

        handleClickOutside(event) {
            if (this.$el && !this.$el.contains(event.target)) {
                this.closeDropdown();
            }
        },

        clearSelection() {
            this.selectedOption = null;
            this.displayedInput = null;
            this.$emit('update:modelValue', null);
        },

        // Load selected option data on mount if modelValue exists
        async loadSelectedOption() {
            if (this.modelValue && this.api) {
                try {
                    const response = await this.api.get({
                        id: this.modelValue,
                        per_page: 1
                    });

                    if (response instanceof BaseResponse && response.data && response.data.length) {
                        const option = response.data[0];
                        this.selectedOption = {
                            value: option.id,
                            label: option.name || option.title || option.label || option.value || (new BaseClass(option)).getFullName,
                        };
                        this.displayedInput = this.selectedOption.label;
                    }
                } catch (error) {
                    console.error('Error loading selected option:', error);
                }
            }
        }
    },

    expose: ['focus'],

    async mounted() {
        if (this.apiLink) {
            this.api = new ApiService(this.apiLink);
            await this.loadSelectedOption();
        }

        document.addEventListener("click", this.handleClickOutside);
    },

    beforeUnmount() {
        document.removeEventListener("click", this.handleClickOutside);
        if (this.debounceTimeout) {
            clearTimeout(this.debounceTimeout);
        }
    },

    watch: {
        async modelValue(newVal, oldVal) {
            if (newVal !== oldVal) {
                if (newVal) {
                    await this.loadSelectedOption();
                } else {
                    this.clearSelection();
                }
            }
        },

        displayedInput(newVal) {
            if (!newVal && this.selectedOption) {
                this.clearSelection();
            }
        },

        'api.processing'(newVal) {
            if (!newVal && this.autoFocus) {
                this.$nextTick(() => {
                    this.$refs?.textInput?.focus();
                });
            }
        }
    },

    computed: {
        processing() {
            return this.api?.processing;
        },

        dynamicLabel() {
            if (this.processing && !this.isLoadingMore) {
                return `${this.label} (loading)`;
            }
            return this.label;
        },

        hasOptions() {
            return this.filteredOptions && this.filteredOptions.length > 0;
        },

        showLoadingMore() {
            return this.isLoadingMore && this.hasOptions;
        },

        emptyStateMessage() {
            if (this.processing && !this.hasOptions) {
                return 'Loading options...';
            }
            if (this.currentSearch && !this.hasOptions) {
                return 'No results found';
            }
            if (!this.currentSearch && !this.hasOptions) {
                return 'Type to search...';
            }
            return '';
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
                :title="title"
                :label="dynamicLabel"
                :error="$attrs.error"
                :required="required"
                :show-clear="!disabled"
                :disabled="disabled"
                v-model="displayedInput"
                :placeholder="placeholder"
                @focusin="toggleDropdown()"
                @click="toggleDropdown()"
                @input="debounceApiCall($event.target.value)"
                @clear="clearSelection"
            />

            <transition-container>
                <div
                    v-show="showDropdown"
                    class="absolute left-0 mt-2 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-lg bg-white z-[999] min-w-full max-w-[20rem]"
                    style="top: 100%;"
                >
                    <!-- Dropdown Header -->
                    <div
                        v-if="!processing || hasOptions"
                        class="text-xs text-gray-500 px-3 py-2 border-b border-gray-100 bg-gray-50 rounded-t-md"
                    >
                        <p v-if="hasOptions">
                            {{ filteredOptions.length }} option{{ filteredOptions.length !== 1 ? 's' : '' }}
                            <span v-if="hasMoreData" class="text-gray-400">(scroll for more)</span>
                        </p>
                        <p v-else>{{ emptyStateMessage }}</p>
                    </div>

                    <!-- Options List -->
                    <div
                        v-if="hasOptions"
                        class="max-h-48 overflow-y-auto"
                        @scroll="handleDropdownScroll"
                    >
                        <div
                            v-for="option in filteredOptions"
                            :key="option.value"
                            @click="selectOption(option)"
                            class="px-3 py-2 hover:bg-indigo-50 cursor-pointer border-b border-gray-50 last:border-b-0 transition-colors duration-150"
                            :class="{
                                'bg-indigo-100 text-indigo-900': selectedOption?.value === option.value,
                                'text-gray-900': selectedOption?.value !== option.value
                            }"
                        >
                            <div class="truncate" :title="option.label">
                                {{ option.label }}
                            </div>
                        </div>

                        <!-- Loading More Indicator -->
                        <div
                            v-if="showLoadingMore"
                            class="px-3 py-2 text-center text-sm text-gray-500 border-t border-gray-100"
                        >
                            Loading more options...
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else-if="!processing"
                        class="px-3 py-4 text-center text-gray-500 text-sm"
                    >
                        {{ emptyStateMessage }}
                    </div>

                    <!-- Initial Loading State -->
                    <div
                        v-else
                        class="px-3 py-4 text-center text-gray-500 text-sm"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-500"></div>
                            <span>Loading options...</span>
                        </div>
                    </div>
                </div>
            </transition-container>
        </div>
    </div>
</template>
