<script>
    export default {
        name: "Logo",
        props: {
            classes: {
                type: String,
                default: 'sm:h-12 h-10 w-auto',
            },
        }
    }
</script>
<template>
    <img src="/img/logo.png" alt="Crop Biotechnology Center Logo" :class="classes">
</template>