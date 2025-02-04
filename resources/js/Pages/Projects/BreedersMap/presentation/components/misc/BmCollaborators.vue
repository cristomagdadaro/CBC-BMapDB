<script>
export default {
    name: "BmCollaborators",
    data() {
        return {
            collaborators: [
                {
                    name: "DA - Biotechnology Program Office (DA-BPO)",
                    link: "/",
                },
                {
                    name: "Philippine Rice Research Institute (PhilRice)",
                    link: "/",
                },
                {
                    name: "DA - Regional Field Office 3 (DA-RFO 3)",
                    link: "/",
                },
                {
                    name: "Regional Crop Protection Center (DA-RCPC) Region 3",
                    link: "/",
                },
                {
                    name: "BPI - National Seed Quality Control Services (BPI-NSQCS) Region 3",
                    link: "/",
                },
                {
                    name: "Central Luzon State University (CLSU)",
                    link: "/",
                },
                {
                    name: "Aurora State College of Technology (ASCOT)",
                    link: "/",
                },
                {
                    name: "Tarlac Agricultural University (TAU)",
                    link: "/",
                },
                {
                    name: "Nueva Ecija University of Science and Technology (NEUST) Gabaldon",
                    link: "/",
                },
                {
                    name: "Bulacan State University (BulSU)",
                    link: "/",
                },
                {
                    name: "Pangasinan State University (PSU)",
                    link: "/",
                },
            ]
        }
    }
}
</script>

<template>
    <div class="flex flex-col gap-3 py-5">
        <h3 class="text-center text-subtitle">
            Data Sources
        </h3>
        <ul class="text-normal flex flex-col items-center gap-1 sm:gap-2">
            <li v-for="collaborator in collaborators">
                <a :href="collaborator.link" target="_blank" >
                    {{ collaborator.name }}
                </a>
            </li>
        </ul>
    </div>
</template>

<style scoped>

</style>
