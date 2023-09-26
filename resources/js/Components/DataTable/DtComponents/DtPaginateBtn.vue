<template>
    <button :class="{ 'opacity-50 cursor-not-allowed': disabled }" class="flex items-center text-sm flex-shrink-0 py-0.5 px-2 mx-0.5 shadow-sm rounded-sm hover:bg-gray-200 border-gray-200 bg-gray-300 border"
            @click="func"><slot /></button>
</template>
<script>
    export default {
        props: {
            disabled: {
                type: Boolean,
                required: true,
            },
            func: {
                type: Function,
                required: true,
            },
        },
    };
</script>
