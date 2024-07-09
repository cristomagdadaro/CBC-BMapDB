<script>
import { BreedersMapPages } from "@/Pages/Projects/BreedersMap/components/components.js";
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";

export default {
    name: "BreedersTable",
    computed: {
        CRCMDatatable() {
            return CRCMDatatable
        }
    },
    components: {
        CRCMDatatable,
    },
    setup() {
        return {
            BreedersMapPages,
        };
    },
};
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase select-none">Breeders</h1>
    <CRCMDatatable
        :base-url="BreedersMapPages.api.breeder.path"
        :base-model="BreedersMapPages.api.breeder.model"
        :add-form="BreedersMapPages.api.breeder.create.component"
        :edit-form="BreedersMapPages.api.breeder.edit.component"
        :view-form="BreedersMapPages.api.breeder.view.path"
    />
</template>
