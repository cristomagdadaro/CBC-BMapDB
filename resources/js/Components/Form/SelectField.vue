<!--
Enhanced SelectField with modern dropdown UI
Options Array format: { label: 'Sample', value: 'sample' }
-->
<script setup>
import { onMounted, ref, computed, nextTick, watch, onUnmounted } from 'vue';
import InputError from "@/Components/InputError.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import CaretDown from "@/Components/Icons/CaretDown.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

const props = defineProps({
    modelValue: [String, Number],
    id: String,
    label: String,
    options: {
        type: Array,
        default: () => [],
        validator: (options) => options.every(opt => opt.hasOwnProperty('label') && opt.hasOwnProperty('value'))
    },
    error: {
        type: [String, Array],
        default: null,
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: null,
    },
    placeholder: {
        type: String,
        default: 'Select an option...',
    },
    searchable: {
        type: Boolean,
        default: true,
    },
    clearable: {
        type: Boolean,
        default: true,
    },
    maxHeight: {
        type: String,
        default: '12rem', // max-h-48
    },
    allowEmpty: {
        type: Boolean,
        default: true,
    }
});

const emit = defineEmits(['update:modelValue', 'change', 'open', 'close']);

const dropdownRef = ref(null);
const searchInput = ref(null);
const showDropdown = ref(false);
const searchQuery = ref('');
const highlightedIndex = ref(-1);

// Enhanced computed properties
const hasError = computed(() => {
    if (!props.error) return false;
    return Array.isArray(props.error) ? props.error.length > 0 : Boolean(props.error);
});

const errorMessage = computed(() => {
    if (!hasError.value) return null;
    return Array.isArray(props.error) ? props.error[0] : props.error;
});

const selectedOption = computed(() => {
    if (!props.modelValue) return null;
    return props.options.find(option => option.value === props.modelValue) || null;
});

const displayValue = computed(() => {
    return selectedOption.value?.label || props.placeholder;
});

const filteredOptions = computed(() => {
    if (!props.searchable || !searchQuery.value) {
        return props.options;
    }

    const query = searchQuery.value.toLowerCase();
    return props.options.filter(option =>
        option.label.toLowerCase().includes(query) ||
        option.value.toString().toLowerCase().includes(query)
    );
});

const hasOptions = computed(() => filteredOptions.value.length > 0);

const canClear = computed(() =>
    props.clearable && selectedOption.value && !props.disabled && !props.required
);

const showSearchInput = computed(() => props.searchable && showDropdown.value);

// Dynamic classes
const triggerClasses = computed(() => [
    'relative w-full cursor-pointer rounded-md border bg-white py-2 pl-3 pr-10 text-left shadow-sm transition-all duration-200',
    'focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500',
    {
        'border-red-300 focus:border-red-500 focus:ring-red-500': hasError.value,
        'border-gray-300 hover:border-gray-400': !hasError.value,
        'opacity-60 cursor-not-allowed': props.disabled,
        'border-indigo-500 ring-1 ring-indigo-500': showDropdown.value && !hasError.value
    }
]);

const displayTextClasses = computed(() => [
    'block truncate text-sm',
    {
        'text-gray-500': !selectedOption.value,
        'text-gray-900': selectedOption.value,
        'cursor-not-allowed': props.disabled
    }
]);

// Methods
const toggleDropdown = () => {
    if (props.disabled) return;

    if (showDropdown.value) {
        closeDropdown();
    } else {
        openDropdown();
    }
};

const openDropdown = () => {
    showDropdown.value = true;
    highlightedIndex.value = selectedOption.value ?
        filteredOptions.value.findIndex(opt => opt.value === props.modelValue) : -1;

    emit('open');

    nextTick(() => {
        if (props.searchable && searchInput.value) {
            searchInput.value.focus();
        }
    });
};

const closeDropdown = () => {
    showDropdown.value = false;
    searchQuery.value = '';
    highlightedIndex.value = -1;
    emit('close');
};

const selectOption = (option) => {
    const oldValue = props.modelValue;
    emit('update:modelValue', option.value);

    if (oldValue !== option.value) {
        emit('change', option);
    }

    closeDropdown();
};

const clearSelection = () => {
    if (!canClear.value) return;

    const oldValue = props.modelValue;
    emit('update:modelValue', null);

    if (oldValue !== null) {
        emit('change', null);
    }

    closeDropdown();
};

const handleKeydown = (event) => {
    if (props.disabled) return;

    switch (event.key) {
        case 'ArrowDown':
            event.preventDefault();
            if (!showDropdown.value) {
                openDropdown();
            } else {
                navigateOptions(1);
            }
            break;

        case 'ArrowUp':
            event.preventDefault();
            if (showDropdown.value) {
                navigateOptions(-1);
            }
            break;

        case 'Enter':
            event.preventDefault();
            if (showDropdown.value) {
                if (highlightedIndex.value >= 0 && filteredOptions.value[highlightedIndex.value]) {
                    selectOption(filteredOptions.value[highlightedIndex.value]);
                }
            } else {
                openDropdown();
            }
            break;

        case 'Escape':
            event.preventDefault();
            closeDropdown();
            break;

        case 'Tab':
            if (showDropdown.value) {
                closeDropdown();
            }
            break;
    }
};

const navigateOptions = (direction) => {
    const optionsLength = filteredOptions.value.length;
    if (optionsLength === 0) return;

    let newIndex = highlightedIndex.value + direction;

    if (newIndex < 0) {
        newIndex = optionsLength - 1;
    } else if (newIndex >= optionsLength) {
        newIndex = 0;
    }

    highlightedIndex.value = newIndex;

    // Scroll highlighted option into view
    nextTick(() => {
        const optionElement = dropdownRef.value?.querySelector(`[data-option-index="${newIndex}"]`);
        optionElement?.scrollIntoView({ block: 'nearest' });
    });
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        closeDropdown();
    }
};

const handleSearchInput = (event) => {
    searchQuery.value = event.target.value;
    highlightedIndex.value = -1;
};

// Watchers
watch(filteredOptions, () => {
    highlightedIndex.value = -1;
});

// Lifecycle
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

// Expose methods
defineExpose({
    focus: () => {
        if (props.searchable && searchInput.value) {
            searchInput.value.focus();
        }
    },
    open: openDropdown,
    close: closeDropdown,
    clear: clearSelection
});
</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent space-y-1">
        <!-- Label and Error Row -->
        <div v-if="label" class="flex justify-between items-center px-1 gap-2 min-h-[1.25rem]">
            <label

                :for="id"
                class="flex gap-0.5 items-center whitespace-nowrap text-sm font-medium text-gray-700 cursor-pointer"
                :class="{ 'text-red-600': hasError }"
                @click="toggleDropdown"
            >
                {{ label }}
                <span v-if="required" class="text-red-500 font-bold text-sm ml-1" aria-label="required">*</span>
            </label>

            <div class="flex items-center gap-2">
                <InputError v-if="hasError" :message="errorMessage" class="text-xs" />
                <button
                    v-else-if="title"
                    type="button"
                    :title="title"
                    :aria-label="`Help: ${title}`"
                    class="text-gray-600 opacity-70 font-bold text-[0.6rem] leading-none border border-gray-400 rounded-full p-0.5 px-1 hover:bg-cbc-dark-green hover:text-white hover:border-cbc-dark-green transition-colors duration-200 cursor-help focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
                >
                    ?
                </button>
            </div>
        </div>

        <!-- Select Trigger -->
        <div class="relative" ref="dropdownRef">
            <button
                type="button"
                :class="triggerClasses"
                :disabled="disabled"
                :aria-expanded="showDropdown"
                :aria-haspopup="true"
                :aria-invalid="hasError"
                :aria-describedby="hasError ? `${id}-error` : null"
                @click="toggleDropdown"
                @keydown="handleKeydown"
            >
                <span :class="displayTextClasses">
                    {{ displayValue }}
                </span>

                <!-- Action Icons -->
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 gap-1">
                    <!-- Clear Button -->
                    <button
                        v-if="canClear"
                        type="button"
                        class="pointer-events-auto text-gray-400 hover:text-gray-600 p-0.5 rounded transition-colors duration-150"
                        @click.stop="clearSelection"
                        title="Clear selection"
                        aria-label="Clear selection"
                    >
                        <CloseIcon class="h-4 w-4" />
                    </button>

                    <!-- Dropdown Arrow -->
                    <CaretDown
                        class="h-5 w-5 text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': showDropdown }"
                        aria-hidden="true"
                    />
                </span>
            </button>

            <!-- Dropdown Menu -->
            <TransitionContainer>
                <div
                    v-show="showDropdown"
                    class="absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg border border-gray-200 focus:outline-none"
                    role="listbox"
                    :aria-labelledby="id"
                >
                    <!-- Search Input -->
                    <div v-if="showSearchInput" class="p-2 border-b border-gray-100">
                        <input
                            ref="searchInput"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                            placeholder="Search options..."
                            v-model="searchQuery"
                            @input="handleSearchInput"
                            @keydown="handleKeydown"
                        />
                    </div>

                    <!-- Options Header -->
                    <div
                        v-if="hasOptions"
                        class="text-xs text-gray-500 px-3 py-2 border-b border-gray-100 bg-gray-50"
                    >
                        {{ filteredOptions.length }} option{{ filteredOptions.length !== 1 ? 's' : '' }}
                        <span v-if="searchQuery" class="text-gray-400">(filtered)</span>
                    </div>

                    <!-- Options List -->
                    <div
                        v-if="hasOptions"
                        class="overflow-auto max-h-48"
                        :style="{ maxHeight: maxHeight }"
                    >
                        <div
                            v-for="(option, index) in filteredOptions"
                            :key="option.value"
                            :data-option-index="index"
                            class="cursor-pointer  relative px-3 py-2 transition-colors duration-150"
                            :class="{
                                'bg-indigo-100 text-indigo-900': selectedOption?.value === option.value,
                                'bg-gray-100': highlightedIndex === index && selectedOption?.value !== option.value,
                                'text-gray-900 hover:bg-gray-50': selectedOption?.value !== option.value && highlightedIndex !== index
                            }"
                            role="option"
                            :aria-selected="selectedOption?.value === option.value"
                            @click="selectOption(option)"
                            @mouseenter="highlightedIndex = index"
                        >
                            <div class="flex items-center justify-between">
                                <span class="truncate">{{ option.label }}</span>
                                <span
                                    v-if="selectedOption?.value === option.value"
                                    class="text-indigo-600 ml-2"
                                    aria-hidden="true"
                                >
                                    ✓
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else
                        class="px-3 py-4 text-center text-gray-500 text-sm"
                    >
                        <span v-if="searchQuery">No options match "{{ searchQuery }}"</span>
                        <span v-else>No options available</span>
                    </div>
                </div>
            </TransitionContainer>
        </div>

        <!-- Error message for screen readers -->
        <div v-if="hasError" :id="`${id}-error`" class="sr-only">
            {{ errorMessage }}
        </div>
    </div>
</template>
