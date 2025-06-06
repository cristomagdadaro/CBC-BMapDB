<script>
export default {
    name: "BmCollaborators",
    data() {
        return {
            logo_dir: "/img/collaborators/",
            collaborators: [
                {
                    name: "Philippine Rice Research Institute (PhilRice)",
                    link: "/",
                    logo: "PhilRice.png",
                    visible: true,
                },
                {
                    name: "DA - Biotechnology Program Office (DA-BPO)",
                    link: "/",
                    logo: "DA-BPO.png",
                    visible: true,
                },
                {
                    name: "DA - Regional Field Office 3 (Central Luzon)",
                    link: "/",
                    logo: "DA-RFO-III.png",
                    visible: true,
                },
                {
                    name: "DA - Regional Field Office 1 (La Union)",
                    link: "/",
                    logo: "DA-RFO-I.png",
                    visible: true,
                },
                {
                    name: "BPI - National Seed Quality Control Services (BPI-NSQCS) Region 3",
                    link: "/",
                    logo: "BPI-NSQCS.png",
                    visible: true,
                },
                {
                    name: "Regional Crop Protection Center (DA-RCPC) Region 3",
                    link: "/",
                    logo: "DA-RCPC-3.png",
                    visible: true,
                },
                {
                    name: "Central Luzon State University (CLSU)",
                    link: "/",
                    logo: "CLSU.png",
                    visible: true,
                },
                {
                    name: "Aurora State College of Technology (ASCOT)",
                    link: "/",
                    logo: "ASCOT.png",
                    visible: true,
                },
                {
                    name: "Tarlac Agricultural University (TAU)",
                    link: "/",
                    logo: "TAU.png",
                    visible: false,
                },
                {
                    name: "Nueva Ecija University of Science and Technology (NEUST) Gabaldon",
                    link: "/",
                    logo: "NEUST.png",
                    visible: false,
                },
                {
                    name: "Bulacan State University (BulSU)",
                    link: "/",
                    logo: "BulSU.png",
                    visible: true,
                },
                {
                    name: "Pangasinan State University (PSU)",
                    link: "/",
                    logo: "PSU.png",
                    visible: true,
                },
                {
                    name: "Mariano Marcos State University (MMSU)",
                    link: "/",
                    logo: "MMSU.png",
                    visible: true,
                },
                {
                    name: "Visayas State University (VSU)",
                    link: "/",
                    logo: "VSU.png",
                    visible: true,
                },
            ]
        }
    }
}
</script>

<template>
    <div class="flex flex-col gap-3 py-5">
        <h3 class="text-center text-subtitle">
            Collaborators
        </h3>
        <ul class="text-normal grid grid-cols-6 items-center gap-1 sm:gap-2">
           <template v-for="collaborator in collaborators" >
               <li v-if="collaborator.visible" class="drop-shadow-md">
                   <a :href="collaborator.link" target="_blank" >
                       <img :src="logo_dir + collaborator.logo" :alt="collaborator.name" />
                   </a>
               </li>
           </template>
        </ul>
    </div>
</template>

<style scoped>

</style>
