<script>
import RadioField from "@/Components/Form/RadioField.vue";
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";

export default {
    name: "CreateAccountForm",
    components: {
        SelectSearchField,
        RadioField,
        BaseCreateForm,
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
                user_id: null,
                app_id: null,
                approved_at: null,
            },
        };
    },
    methods: {
        close() {
            this.form = {
                user_id: null,
                app_id: null,
                approved_at: null,
            };

            this.$emit('close');
        }
    },
    watch: {
        forceClose() {
            this.$emit('close');
        },
    },
}
</script>

<template>
    <base-create-form :form="form" :forceClose="forceClose">
        <template v-slot:formTitle>
            Create new User Account
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <select-search-field :api-link="route('api.users.index')" :error="errors? errors['user_id']:{}" label="User" v-model="form.user_id" required />
                <select-search-field :api-link="route('api.applications.index')" :error="errors? errors['app_id']:{}" label="App" v-model="form.app_id" required />
            </div>
        </template>
    </base-create-form>
</template>

<style scoped>

</style>
