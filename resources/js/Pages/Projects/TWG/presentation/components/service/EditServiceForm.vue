<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Service from "@/Pages/Projects/TWG/domain/Service";
import User from "@/Modules/core/domain/auth/User";

export default {
    computed: {
        User() {
            return User
        }
    },
    mixins: [FormMixin],
    name: "CreateServiceForm",
    data() {
        return {
            model: Service
        };
    },
}
</script>

<template>
    <base-edit-form :form="form" :force-close="forceClose" @resetForm="resetForm">
        <template #formTitle>
            Register a new Service/Protocol
        </template>
        <template v-slot:formDescription>
            <div v-if="data" class="grid grid-cols-2 text-sm text-gray-600">
                <span>Date created: {{ data.created_at }}</span>
                <span>Last updated: {{ data.updated_at }}</span>
            </div>
        </template>
        <template #formFields>
            <div class="flex flex-col gap-1">
                <select-search-field required :api-link="route('api.twg.experts.index')"  :error="getError('officer_in_charge')" label="Officer In-charge" v-model="form.officer_in_charge" />
                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                    <text-field required :error="getError('name')" label="Type of service" v-model="form.type" />
                    <text-field :error="getError('purpose')" label="Direct Beneficiaries" v-model="form.direct_beneficiaries" />
                    <text-field :error="getError('indirect_beneficiaries')" label="Indirect Beneficiaries" v-model="form.indirect_beneficiaries" />
                    <text-field required :error="getError('cost')" label="Cost" v-model="form.cost" />
                </div>
                <text-field type-input="longtext" required :error="getError('purpose')" label="Purpose" v-model="form.purpose" />
                <select-search-field v-if="isAdmin()" required :api-link="route('api.institutes.index.public')"  :error="getError('institution')" label="Institution / Agency" v-model="form.institution" />
            </div>
        </template>
    </base-edit-form>
</template>
