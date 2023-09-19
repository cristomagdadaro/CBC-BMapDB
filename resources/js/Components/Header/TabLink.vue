<script>
import { Link } from '@inertiajs/vue3';
export default {
    components: {
        Link,
    },
    props: {
        link: {
            type: String,
            required: true,
            default: '#',
        },
    },
};
</script>
<template>
    <li class="flex items-center hover:bg-cbc-yellow-green duration-400">
        <Link :href="link" class="px-3 py-1 text-gray-700 whitespace-nowrap sm:text-sm text-xs"><slot /></Link>
    </li>
</template>
