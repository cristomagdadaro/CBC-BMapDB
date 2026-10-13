<template>
    <div class="flex flex-col justify-center items-center w-full bg-transparent">
        <div class="w-full relative sm:rounded-2xl rounded-xl shadow-2xl backdrop-blur-md bg-white/10 border border-white/20 transition-all duration-500 p-2 sm:p-4">
            <slot />
        </div>
    </div>
</template>
<script>
export default {
    name: "AuthenticationCard",
};
</script>
