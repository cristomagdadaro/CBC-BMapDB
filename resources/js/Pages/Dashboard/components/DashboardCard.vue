<script>
export default {
    name: "DashboardCard"
}
</script>

<template>
    <div class="rounded-md p-5 max-w-[40rem] min-w-[15rem] flex flex-col gap-1 h-fit">
        <div class="font-light">
            <slot name="title"></slot>
        </div>
        <div class="text-sm">
            <slot name="body"></slot>
        </div>
    </div>
</template>

<style scoped>

</style>
