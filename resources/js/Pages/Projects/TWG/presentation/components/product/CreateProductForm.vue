<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Product from "@/Pages/Projects/TWG/domain/Product";

export default {
    mixins: [FormMixin],
    name: "CreateProductForm",
    data() {
        return {
            model: Product
        };
    },
}
</script>

<template>
    <base-create-form :form="form" :force-close="forceClose">
        <template #formTitle>
            Register a new Product
        </template>
        <template #formFields>
            <div class="flex flex-col gap-1">
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field required :error="getError('name')" label="Name" v-model="form.name" />
                    <text-field :error="getError('brand')" label="Brand" v-model="form.brand" />
                    <text-field required :error="getError('purpose')" label="Purpose" v-model="form.purpose" />
                    <text-field :error="getError('cost')" label="Cost" v-model="form.cost" />
                </div>
                <select-search-field required :api-link="route('api.twg.experts.index')"  :error="getError('twg_expert_id')" label="Expert" v-model="form.twg_expert_id" />
            </div>
        </template>
    </base-create-form>
</template>
