<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";

export default {
    mixins: [FormMixin],
    name: "EditBreederForm",
    data() {
        return {
            form: {
                user_id: this.$page.props.auth.user.id,
                name: null,
                phone: null,
                email: null,
                affiliation: null,
                address: null,
            },
        };
    },
};
</script>

<template>
    <base-edit-form :form="form" :force-close="forceClose" @resetForm="resetForm">
        <template v-slot:formTitle>
            Update Breeder Information
        </template>
        <template v-slot:formDescription>
            <div class="grid grid-cols-2 text-sm text-gray-600">
                <span>Date created: {{ form.created_at }}</span>
                <span>Last updated: {{ form.updated_at }}</span>
            </div>
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :show-clear="true" :error="getError('name')" label="Name" v-model="form.name" />
                <text-field :show-clear="true" :error="getError('phone')" label="Phone Number" v-model="form.phone" />
                <text-field required :show-clear="true" :error="getError('email')" label="Email" v-model="form.email" />
                <text-field required :show-clear="true" :error="getError('affiliation')" label="Agency" v-model="form.affiliation" />
            </div>
            <div class="mt-1">
                <text-field :show-clear="true" :error="getError('address')" label="Address" v-model="form.address" />
            </div>
        </template>
    </base-edit-form>
</template>
