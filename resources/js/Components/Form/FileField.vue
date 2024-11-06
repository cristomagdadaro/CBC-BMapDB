<script>
import InputError from "@/Components/InputError.vue";

export default {
    name: "FileField",
    components: {InputError},
    props: {
        modelValue: Object,
        label: String,
        error: {
            type: [String, Array],
            default: () => [],
        },
        required: {
            type: Boolean,
            default: false,
        },
        showClear: {
            type: Boolean,
            default: false,
        },
        id: String,
    }
}
</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent">
        <div class="flex justify-between items-center">
            <label :for="id" class="text-gray-600 flex gap-0.5 items-center">
                {{ label }}
                <span v-if="required" class="text-red-500 font-bold text-xs">*</span>
            </label>
            <InputError v-for="msg in error" :message="msg" />
        </div>
        <div class="flex relative rounded-md shadow-sm border bg-white hover:ring-1 active:ring-1" :class="error && error.length ? 'border-red-300 focus:border-red-500 focus:ring-red-500':'border-gray-300 focus:border-indigo-500 overflow-ellipsis focus:ring-indigo-500'">
            <input :id="id"
                   type="file"
                   @change="$emit('update:modelValue', $event.target.files[0])"
                   class="border-0 w-full rounded-md focus:ring-0 overflow-ellipsis p-2"
            >
        </div>
    </div>
</template>

<style scoped>

</style>
