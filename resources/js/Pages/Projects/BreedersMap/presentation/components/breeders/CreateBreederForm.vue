<template>
    <base-create-form :form="form" :forceClose="forceClose">
        <template v-slot:formTitle>
            Register a new Breeder
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="errors? errors['name']:{}" label="Name" v-model="form.name" />
                <text-field :error="errors? errors['phone']:{}" label="Phone Number" v-model="form.phone" />
                <text-field required :error="errors? errors['email']:{}" label="Email" v-model="form.email" />
                <text-field required :error="errors? errors['agency']:{}" label="Agency/Institution" v-model="form.agency" />
            </div>
            <div class="mt-1">
                <text-field :error="errors? errors['address']:{}" label="Affiliate Address" v-model="form.address" />
            </div>
        </template>
    </base-create-form>
</template>
<script>
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import TextField from "@/Components/Form/TextField.vue";
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";

export default {
    name: "CreateBreederForm",
    components: {
        BaseCreateForm,
        CancelButton,
        CloseIcon,
        TextField,
    },
    props: {
        errors: {
            type: Object,
            default: () => ({})
        },
        forceClose: {
            type: Boolean,
            default: false
        }
    },
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
    methods: {
        close() {
            this.form = {
                name: null,
                phone: null,
                email: null,
                agency: null,
                address: null,
            };

            this.$emit('close');
        }
    },
    watch: {
        forceClose() {
            this.$emit('close');
        },
    },
};
</script>
