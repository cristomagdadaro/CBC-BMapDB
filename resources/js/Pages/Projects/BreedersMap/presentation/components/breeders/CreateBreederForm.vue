<template>
    <base-create-form :form="form" :forceClose="forceClose">
        <template v-slot:formTitle>
            Register a new Breeder
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('name')" label="Name" v-model="form.name" />
                <text-field :error="getError('phone')" label="Phone Number" v-model="form.phone" />
                <text-field required :error="getError('email')" label="Email" v-model="form.email" />
                <text-field required :error="getError('agency')" label="Agency/Institution" v-model="form.agency" />
            </div>
            <div class="mt-1">
                <text-field :error="getError('address')" label="Affiliate Address" v-model="form.address" />
            </div>
        </template>
    </base-create-form>
</template>
<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";

export default {
    mixins: [FormMixin],
    name: "CreateBreederForm",
    data() {
        return {
            form: {
                user_id: this.$page.props.auth.user.id,
                name: null,
                phone: null,
                email: null,
                agency: null,
                address: null,
            },
        };
    },
};
</script>
