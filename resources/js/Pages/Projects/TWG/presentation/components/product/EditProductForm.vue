<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Product from "@/Pages/Projects/TWG/domain/Product";
import User from "@/Modules/core/domain/auth/User";

export default {
    computed: {
        User() {
            return User
        }
    },
    mixins: [FormMixin],
    name: "EditProductForm",
    data() {
        return {
            model: Product
        };
    },
}
</script>

<template>
    <base-edit-form :form="form" :force-close="forceClose" @resetForm="resetForm">
        <template #formTitle>
            Update Product Details
        </template>
        <template v-slot:formDescription>
            <div v-if="data" class="grid grid-cols-2 text-sm text-gray-600">
                <span>Date created: {{ data.created_at }}</span>
                <span>Last updated: {{ data.updated_at }}</span>
            </div>
        </template>
        <template #formFields>
            <div class="flex flex-col gap-1">
                <text-field required :error="getError('name')" label="Name" v-model="form.name" />
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field :error="getError('brand')" label="Brand" v-model="form.brand" />
                    <text-field :error="getError('cost')" label="Cost" v-model="form.cost" />
                </div>
                <text-field type-input="longtext" required :error="getError('purpose')" label="Purpose" v-model="form.purpose" />
                <select-search-field v-if="isAdmin()" required :api-link="route('api.institutes.index.public')"  :error="getError('institution')" label="Institution / Agency" v-model="form.institution" />
            </div>
        </template>
    </base-edit-form>
</template>
