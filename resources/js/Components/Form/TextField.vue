<script setup>
import { onMounted, ref, computed, nextTick, watch } from 'vue';
import InputError from "@/Components/InputError.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import ViewIcon from "@/Components/Icons/ViewIcon.vue";
import UnviewIcon from "@/Components/Icons/UnviewIcon.vue";

const props = defineProps({
    modelValue: [String, Number],
    id: String,
    label: String,
    error: {
        type: [String, Array],
        default: null,
    },
    typeInput: {
        type: String,
        default: 'text',
        validator: (value) => ['text', 'email', 'password', 'number', 'tel', 'url', 'longtext'].includes(value)
    },
    required: {
        type: Boolean,
        default: false,
    },
    showClear: {
        type: Boolean,
        default: true,
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
        default: null,
    },
    readonly: {
        type: Boolean,
        default: false,
    },
    autocomplete: {
        type: String,
        default: null,
    },
    maxlength: {
        type: Number,
        default: null,
        validator: (value) => value === null || value > 0
    },
    minlength: {
        type: Number,
        default: null,
        validator: (value) => value === null || value >= 0
    },
    rows: {
        type: Number,
        default: 4,
        validator: (value) => value > 0
    },
    autoResize: {
        type: Boolean,
        default: false,
    },
    debounce: {
        type: Number,
        default: 0,
        validator: (value) => value >= 0
    }
});

const emit = defineEmits(['update:modelValue', 'clear', 'focus', 'blur', 'input', 'change', 'keydown', 'keyup']);

const input = ref(null);
const showPassword = ref(false);
const debounceTimer = ref(null);

// Enhanced computed properties with better performance
const hasError = computed(() => {
    if (!props.error) return false;
    return Array.isArray(props.error) ? props.error.length > 0 : Boolean(props.error);
});

const errorMessage = computed(() => {
    if (!hasError.value) return null;
    return Array.isArray(props.error) ? props.error[0] : props.error;
});

const hasValue = computed(() => {
    const value = props.modelValue;
    return value !== null && value !== undefined && value !== '';
});

const showClearButton = computed(() =>
    hasValue.value && props.showClear && !props.disabled && !props.readonly
);

const showPasswordToggle = computed(() =>
    props.typeInput === 'password' && !props.disabled && !props.readonly
);

const inputType = computed(() => {
    if (props.typeInput === 'password') {
        return showPassword.value ? 'text' : 'password';
    }
    return props.typeInput;
});

const isTextarea = computed(() => props.typeInput === 'longtext');

// Optimized dynamic classes with memoization
const containerClasses = computed(() => {
    const baseClasses = 'flex relative rounded-md shadow-sm border bg-white transition-all duration-200';
    const stateClasses = {
        'border-red-300 focus-within:border-red-500 focus-within:ring-1 focus-within:ring-red-500': hasError.value,
        'border-gray-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 hover:border-gray-400': !hasError.value,
        'opacity-60 cursor-not-allowed': props.disabled,
        'bg-gray-50': props.readonly || props.disabled
    };

    return [baseClasses, stateClasses];
});

const inputClasses = computed(() => {
    const baseClasses = 'border-0 w-full text-gray-900 focus:ring-0 bg-transparent transition-colors duration-200 overflow-hidden overflow-ellipsis';
    const stateClasses = {
        'rounded-md': !isTextarea.value,
        'cursor-not-allowed': props.disabled,
        'focus:outline-none': props.readonly,
        'pr-16': showPasswordToggle.value && showClearButton.value,
        'pr-8': (showPasswordToggle.value || showClearButton.value) && !(showPasswordToggle.value && showClearButton.value)
    };

    return [baseClasses, stateClasses];
});

const textareaClasses = computed(() => {
    const baseClasses = 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm resize-vertical transition-all duration-200';
    const stateClasses = {
        'border-red-300 focus:border-red-500 focus:ring-red-500': hasError.value,
        'opacity-60 cursor-not-allowed bg-gray-50': props.disabled,
        'bg-gray-50 focus:outline-none': props.readonly,
        'resize-none': props.autoResize
    };

    return [baseClasses, stateClasses];
});

const characterCount = computed(() => {
    if (!isTextarea.value || !props.maxlength) return null;
    const length = props.modelValue?.toString().length || 0;
    const isNearLimit = length > props.maxlength * 0.8;
    const isOverLimit = length > props.maxlength;

    return {
        current: length,
        max: props.maxlength,
        isNearLimit,
        isOverLimit,
        percentage: (length / props.maxlength) * 100
    };
});

// Enhanced methods with better error handling
const emitWithDebounce = (eventName, value) => {
    if (props.debounce > 0) {
        if (debounceTimer.value) {
            clearTimeout(debounceTimer.value);
        }
        debounceTimer.value = setTimeout(() => {
            emit(eventName, value);
        }, props.debounce);
    } else {
        emit(eventName, value);
    }
};

const handleInput = (event) => {
    const value = event.target.value;

    // Auto-resize textarea if enabled
    if (props.autoResize && isTextarea.value) {
        autoResizeTextarea(event.target);
    }

    emit('update:modelValue', value);
    emitWithDebounce('input', value);
};

const handleChange = (event) => {
    emit('change', event.target.value);
};

const handleFocus = (event) => {
    emit('focus', event);
};

const handleBlur = (event) => {
    emit('blur', event);
};

const handleKeydown = (event) => {
    emit('keydown', event);

    // Handle special key combinations
    if (event.key === 'Escape' && hasValue.value && props.showClear) {
        clearField();
        event.preventDefault();
    }
};

const handleKeyup = (event) => {
    emit('keyup', event);
};

const clearField = () => {
    emit('update:modelValue', '');
    emit('clear');

    nextTick(() => {
        if (input.value) {
            input.value.focus();
        }
    });
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;

    nextTick(() => {
        if (input.value) {
            input.value.focus();
            // Maintain cursor position
            const length = props.modelValue?.toString().length || 0;
            input.value.setSelectionRange(length, length);
        }
    });
};

const autoResizeTextarea = (element) => {
    if (!element || !props.autoResize) return;

    element.style.height = 'auto';
    element.style.height = `${element.scrollHeight}px`;
};

const focus = () => {
    if (input.value) {
        input.value.focus();
    }
};

const blur = () => {
    if (input.value) {
        input.value.blur();
    }
};

const select = () => {
    if (input.value) {
        input.value.select();
    }
};

// Watch for auto-resize on value changes
watch(() => props.modelValue, () => {
    if (props.autoResize && isTextarea.value && input.value) {
        nextTick(() => {
            autoResizeTextarea(input.value);
        });
    }
});

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value.focus();
    }

    // Initial auto-resize for textarea
    if (props.autoResize && isTextarea.value && input.value) {
        autoResizeTextarea(input.value);
    }
});

// Cleanup on unmount
const cleanup = () => {
    if (debounceTimer.value) {
        clearTimeout(debounceTimer.value);
    }
};

// Enhanced expose with more methods
defineExpose({
    focus,
    blur,
    select,
    element: input,
    clearField,
    togglePasswordVisibility,
    hasValue,
    hasError
});

// Cleanup on component unmount
onMounted(() => {
    return cleanup;
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

        <!-- Input Container -->
        <div v-if="!isTextarea" :class="containerClasses">
            <input
                :id="id"
                ref="input"
                :type="inputType"
                :class="inputClasses"
                :value="modelValue"
                :disabled="disabled"
                :readonly="readonly"
                :placeholder="placeholder"
                :autocomplete="autocomplete"
                :maxlength="maxlength"
                :minlength="minlength"
                :required="required"
                :aria-invalid="hasError"
                :aria-describedby="hasError ? `${id}-error` : null"
                @input="handleInput"
                @change="handleChange"
                @focus="handleFocus"
                @blur="handleBlur"
                @keydown="handleKeydown"
                @keyup="handleKeyup"
            />

            <!-- Action Buttons Container -->
            <div
                v-if="showPasswordToggle || showClearButton"
                class="absolute right-0 h-full flex items-center pr-2 gap-1"
                role="group"
                aria-label="Field actions"
            >
                <!-- Password Toggle -->
                <button
                    v-if="showPasswordToggle"
                    type="button"
                    :title="showPassword ? 'Hide password' : 'Show password'"
                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                    @click="togglePasswordVisibility"
                    class="text-cbc-dark-green bg-transparent p-1 rounded hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
                >
                    <ViewIcon v-if="!showPassword" class="w-4 h-4" aria-hidden="true" />
                    <UnviewIcon v-else class="w-4 h-4" aria-hidden="true" />
                </button>

                <!-- Clear Button -->
                <button
                    v-if="showClearButton"
                    type="button"
                    title="Clear field"
                    aria-label="Clear field"
                    @click="clearField"
                    class="text-cbc-dark-green bg-transparent p-1 rounded hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
                >
                    <CloseIcon class="w-4 h-4" aria-hidden="true" />
                </button>
            </div>
        </div>

        <!-- Textarea for longtext -->
        <textarea
            v-else
            :id="id"
            ref="input"
            :class="textareaClasses"
            :value="modelValue"
            :disabled="disabled"
            :readonly="readonly"
            :placeholder="placeholder"
            :maxlength="maxlength"
            :minlength="minlength"
            :rows="rows"
            :required="required"
            :aria-invalid="hasError"
            :aria-describedby="hasError ? `${id}-error` : null"
            @input="handleInput"
            @change="handleChange"
            @focus="handleFocus"
            @blur="handleBlur"
            @keydown="handleKeydown"
            @keyup="handleKeyup"
        />

        <!-- Enhanced Character count for textarea -->
        <div v-if="characterCount" class="flex justify-between items-center text-xs px-1">
            <div class="text-gray-400">
                <span v-if="characterCount.isOverLimit" class="text-red-500">Character limit exceeded</span>
                <span v-else-if="characterCount.isNearLimit" class="text-amber-500">Approaching limit</span>
            </div>
            <div
                class="font-mono"
                :class="{
                    'text-red-500': characterCount.isOverLimit,
                    'text-amber-500': characterCount.isNearLimit && !characterCount.isOverLimit,
                    'text-gray-500': !characterCount.isNearLimit
                }"
            >
                {{ characterCount.current }}/{{ characterCount.max }}
            </div>
        </div>

        <!-- Error message for screen readers -->
        <div v-if="hasError" :id="`${id}-error`" class="sr-only">
            {{ errorMessage }}
        </div>
    </div>
</template>
