<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";

export default {
    mixins: [FormMixin],
    name: "CreateAccountForm",
    data() {
        return {
            form: {
                user_id: null,
                app_id: null,
                approved_at: null,
            },
        };
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
                <select-search-field :api-link="route('api.users.index')" :error="getError('user_id')" label="User" v-model="form.user_id" required />
                <select-search-field :api-link="route('api.applications.index')" :error="getError('app_id')" label="App" v-model="form.app_id" required />
            </div>
        </template>
    </base-create-form>
</template>

<style scoped>

</style>
