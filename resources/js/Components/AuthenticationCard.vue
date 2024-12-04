<template>
    <div class="flex flex-col justify-center items-center w-full bg-transparent">
        <div class="w-full sm:max-w-2xl p-7 relative sm:rounded-lg bg-cbc-yellow shadow-2xl border-0 border-gray-100">
            <slot />
        </div>
    </div>
</template>
<script>
export default {
    name: "AuthenticationCard",
};
</script>
