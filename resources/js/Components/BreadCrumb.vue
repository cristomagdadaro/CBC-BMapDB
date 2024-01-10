<template>
    <nav class="breadcrumb">
        <ul>
            <li v-for="(crumb, index) in crumbs" :key="index">
                <Link :href="crumb.to">{{ crumb.label }}</Link>
            </li>
        </ul>
    </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3';
export default {
    components: {
        Link,
    },
    computed: {
        crumbs() {
            return this.$page.props.breadcrumbs;
        },
    },
};
</script>
