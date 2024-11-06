<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Service from "@/Pages/Projects/TWG/domain/Service";

export default {
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
    <base-create-form :form="form" :force-close="forceClose">
        <template #formTitle>
            Register a new Service/Protocol
        </template>
        <template #formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('name')" label="Type of service" v-model="form.type" />
                <text-field :error="getError('purpose')" label="Direct Beneficiaries" v-model="form.direct_beneficiaries" />
                <select-search-field required :api-link="route('api.twg.experts.index')"  :error="getError('twg_expert_id')" label="Expert" v-model="form.twg_expert_id" />
                <text-field :error="getError('indirect_beneficiaries')" label="Indirect Beneficiaries" v-model="form.indirect_beneficiaries" />
                <text-field required :error="getError('officer_in_charge')" label="Officer In-charge" v-model="form.officer_in_charge" />
                <text-field required :error="getError('cost')" label="Cost" v-model="form.cost" />
            </div>
            <text-field type-input="longtext" required :error="getError('purpose')" label="Purpose" v-model="form.purpose" />
        </template>
    </base-create-form>
</template>
