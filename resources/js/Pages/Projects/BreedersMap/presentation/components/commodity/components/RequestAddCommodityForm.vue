<script>
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import FormMixin from "@/Pages/mixins/FormMixin";

export default {
    name: "RequestAddCommodityForm",
    components: {BaseCreateForm},
    mixins: [FormMixin],
    mounted() {
        this.form = {
            name: null,
            scientific_name: null,
        }
    }
}
</script>

<template>
<base-create-form :form="form" :force-close="forceClose">
    <template v-slot:formTitle>
        Request to add Commodity
    </template>
    <template v-slot:formDescription>
        <p class="text-sm text-gray-600">
            Please fill out this form to include your commodity of interest.
        </p>
        <br/>
        <p class="text-sm text-gray-600">
            Might take some time to process your request. Please contact <a href="mailto:pin.dacbc@gmail.com">pin.dacbc@gmail.com</a> for queries.
        </p>
    </template>
    <template v-slot:formFields>
        <div class="grid grid-cols-2 gap-2">
            <text-field required :title="getTitle('Common Name')" :error="getError('name')" label="Common Name" v-model="form.name" />
            <text-field required :title="getTitle('Scientific Name')" :error="getError('scientific_name')" label="Scientific Name" v-model="form.scientific_name" />
        </div>
    </template>
</base-create-form>
</template>

<style scoped>

</style>
