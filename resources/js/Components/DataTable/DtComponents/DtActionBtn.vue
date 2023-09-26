<template>
    <Link v-if="href" :href="href" class="hover:bg-opacity-80 hover:shadow-md active:scale-95 active:bg-opacity-100 text-white flex items-center text-sm flex-shrink-0 py-0.5 px-2 shadow-sm rounded-sm">
        <slot />
    </Link>
    <button v-else class="hover:bg-opacity-80 hover:shadow-md active:scale-95 active:bg-opacity-100 text-white flex items-center text-sm flex-shrink-0 py-0.5 px-2 shadow-sm rounded-sm">
        <slot />
    </button>
</template>
<script>
    import { Link } from "@inertiajs/vue3";
    export default {
        props: {
            href: {
                type: String,
                required: false,
            },
        },
        components: {
            Link,
        },
        methods: {
            route(name, params, absolute) {
                return route(name, params, absolute, this.$page.locale);
            },
        },
    };
</script>
