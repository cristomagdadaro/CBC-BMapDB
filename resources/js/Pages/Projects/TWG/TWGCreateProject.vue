<template>
    <div>
        <Form v-if="!props.dataLink" @submitted="saveForm">
            <template #form>
                <div class="grid gap-2">
                    <TextField label="Title" type-input="longtext" name="title" v-model="form.title" :required="true" longtext />
                    <TextField label="Objective" type-input="longtext" name="objective" v-model="form.objective" longtext />
                    <TextField label="Expected Output" name="expected_output" v-model="form.expected_output" :required="true" />
                    <TextField label="Project Leader" name="project_leader" v-model="form.project_leader" />
                    <TextField label="Funding Agency" name="funding_agency" v-model="form.funding_agency" :required="true" />
                    <TextField label="Duration (years)" name="duration" v-model="form.duration" />
                    <SelectField label="Status" name="status" v-model="form.status" :required="true" :options="ProjectStatus" />
                </div>
            </template>
            <template #actions>
                <div>
                    <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                        Saved
                    </ActionMessage>

                    <PrimaryButton class="mt-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </PrimaryButton>
                </div>
            </template>
        </Form>
        <Form v-else @submitted="updateForm">
            <template #form>
                <div class="grid gap-2">
                    <TextField label="Title" type-input="longtext" name="title" v-model="form.title" :required="true" longtext />
                    <TextField label="Objective" type-input="longtext" name="objective" v-model="form.objective" longtext />
                    <TextField label="Expected Output" name="expected_output" v-model="form.expected_output" :required="true" />
                    <TextField label="Project Leader" name="project_leader" v-model="form.project_leader" />
                    <TextField label="Funding Agency" name="funding_agency" v-model="form.funding_agency" :required="true" />
                    <TextField label="Duration (years)" name="duration" v-model="form.duration" />
                    <SelectField label="Status" name="status" v-model="form.status" :required="true" :options="ProjectStatus" />
                </div>
            </template>
            <template #actions>
                <div>
                    <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                        Updated
                    </ActionMessage>

                    <PrimaryButton class="mt-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Update
                    </PrimaryButton>
                </div>
            </template>
        </Form>
    </div>
</template>
<script setup>
import ActionMessage from "@/Components/ActionMessage.vue";
import Form from "@/Components/Form/Form.vue";
import TextField from "@/Components/Form/TextField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import SelectField from "@/Components/Form/SelectField.vue";
import { ProjectStatus } from "@/Pages/constants.ts";
import {onMounted} from "vue";

const page = usePage();

const props = defineProps({
    dataLink: {
        type: String,
        default: null,
    },
});

const form = useForm({
    id: '',
    title: '',
    objective: '',
    expected_output: '',
    project_leader: '',
    funding_agency: '',
    duration: '',
    status: '',
});

const saveForm = () => {
    form.transform((data) => ({
        ...data,
        twg_expert_id: page.props.auth.user.id,
    })).post(route('twg.project.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        }
    });
}

const updateForm = () => {
    console.log(route('twg.project.update', form.id));
    form.transform((data)=>({
        ...data,
        twg_expert_id: page.props.auth.user.id,
    })).put(route('twg.project.update', form.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: (error) => {
            console.log(error);
        }
    });
}

onMounted(() => {
    if(props.dataLink) {
        axios.get(props.dataLink).then((response) => {
            form.id = response.data.id;
            form.title = response.data.title;
            form.objective = response.data.objective;
            form.expected_output = response.data.expected_output;
            form.project_leader = response.data.project_leader;
            form.funding_agency = response.data.funding_agency;
            form.duration = response.data.duration;
            form.status = response.data.status;
        });
    }
})

</script>
