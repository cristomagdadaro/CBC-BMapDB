<template>
    <div class="flex flex-col justify-center items-center w-fit bg-transparent">
        <div class="w-full p-7 relative w-fit sm:rounded-lg bg-cbc-yellow shadow-2xl border-0 border-gray-100">
            <slot />
        </div>
    </div>
</template>
<script>
export default {
    name: "AuthenticationCard",
};
</script>
