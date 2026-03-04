<template>
    <div class="flex flex-col justify-center items-center w-fit bg-transparent">
        <div class="w-fit p-7 relative sm:rounded-lg rounded-xl text-white shadow-2xl backdrop-blur-sm bg-white/10 transition-all duration-500">
            <slot />
        </div>
    </div>
</template>
<script>
export default {
    name: "AuthenticationCard",
};
</script>
