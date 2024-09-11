<script>
export default {
    name: "BmCollaborators",
    data() {
        return {
            collaborators: [
                {
                    name: "Association of Southeast Asian Nations (ASEAN)",
                    link: "https://www.asean.org/",
                },
                {
                    name: "Southeast Asian Regional Center for Graduate Study and Research in Agriculture (SEARCA)",
                    link: "https://www.searca.org/",
                },
                {
                    name: "International Rice Research Institute (IRRI)",
                    link: "https://www.irri.org/",
                },
                {
                    name: "International Crops Research Institute for the Semi-Arid Tropics (ICRISAT)",
                    link: "https://www.icrisat.org/",
                },
                {
                    name: "World Bank",
                    link: "https://www.worldbank.org/",
                },
                {
                    name: "Consultative Group on International Agricultural Research (CGIAR)",
                    link: "https://www.cgiar.org/",
                },
            ]
        }
    }
}
</script>

<template>
    <div class="flex flex-col gap-3 py-5">
        <h3 class="text-center text-subtitle">
            Project Collaborators
        </h3>
        <ul class="text-normal">
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
