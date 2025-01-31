<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";
import Role from "@/Pages/Admin/domain/Role";
export default {
    mixins: [FormMixin],
    name: "CreateRoleForm",
    data() {
        return {
            model: Role
        };
    },
}
</script>

<template>
    <base-create-form :form="form" :forceClose="forceClose">
        <template v-slot:formTitle>
            Create new System Role
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('name')" label="Role Name" v-model="form.name" />
                <text-field required :error="getError('guard')" label="Guard" v-model="form.guard" />
            </div>
        </template>
    </base-create-form>
</template>
