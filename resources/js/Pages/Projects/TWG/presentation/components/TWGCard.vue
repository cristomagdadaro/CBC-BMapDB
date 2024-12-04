<script>
import ProfileEmptyIcon from "@/Components/Icons/ProfileEmptyIcon.vue";

export default {
    components: {
        ProfileEmptyIcon,
    },
    props: {
        data: {
            type: Object,
            required: true,
            default: () => ({
                agencyName: "Agency Name",
                contact: "Contact",
                experts: 0,
                projects: 0,
            }),
        }
    }
}
</script>

<template>
    <div class="flex flex-col w-full sm:p-5 p-4 shadow rounded-md bg-gray-100">
        <div class="flex gap-5 justify-between items-center pb-2">
            <div class="flex items-center gap-2">
                <profile-empty-icon class="text-gray-400 w-10 h-10 hidden" />
                <div class="flex flex-col">
                    <h1 class="font-semibold leading-tight">{{ data.affiliation }}</h1>
                    <h2 class="leading-3 opacity-75 text-normal hidden">Institute</h2>
                </div>
            </div>
            <div class="hidden bg-green-200 text-cbc-dark-green font-medium px-3 py-0.5 rounded-full">
                Active
            </div>
        </div>
        <div class="border-y p-2 grid grid-rows-2 gap-3">
            <div class="grid grid-cols-2">
                <div>
                    <h1 class="text-normal text-gray-400">No. Experts</h1>
                    <h2 class="text-normal">{{ data.experts }}</h2>
                </div>
                <div>
                    <h1 class="text-normal text-gray-400">No. Projects</h1>
                    <h2 class="text-normal">{{ data.projects }}</h2>
                </div>
            </div>
            <div class="grid grid-cols-2">
                <div>
                    <h1 class="text-normal text-gray-400">No. Products</h1>
                    <h2 class="text-normal">{{ data.products }}</h2>
                </div>
                <div>
                    <h1 class="text-normal text-gray-400">No. Services</h1>
                    <h2 class="text-normal">{{ data.services }}</h2>
                </div>
            </div>
        </div>
        <div class="flex justify-between pt-2 hidden">
            <div>
                <h1 class="text-normal text-gray-400">Budget Allocated</h1>
                <h2 class="text-normal">null</h2>
            </div>
            <button class="bg-blue-200 text-blue-950 font-medium text-normal px-2 py-0.5 rounded-md">View Details</button>
        </div>
    </div>
</template>

<style scoped>

</style>
