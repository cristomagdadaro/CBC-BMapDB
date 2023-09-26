<template>
    <div class="flex items-center text-sm">
        <label for="dtLength">Show</label>
        <select name="dtLength" id="dtLength" :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                class="rounded-sm shadow-sm text-sm border-gray-200 appearance-none bg-transparent w-auto h-8 text-gray-700 py-1 px-2 pr-7 mx-1 leading-tight focus:outline-none focus:ring-0" >
            <option v-for="opt in options" :value="opt" :key="opt">{{ opt }}</option>
        </select>
        <label class="dtLengthText">entries</label>
    </div>
</template>
<script>
export default {
    props: {
        modelValue: {
            type: [Number,String],
            required: true,
        },
        options: {
            type: Object,
            required: true,
            default: [10, 25, 50, 100],
        }
    },
}
</script>
