<script>
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import OpenAiApiService from "@/Pages/OpenAi/infrastructure/OpenAiApiService";

export default {
    name: "AiChat",
    components: {PrimaryButton, TextField, BaseCreateForm},
    data() {
        return {
            query: null,
            aiApi: null,
            aiResponses: [],
        }
    },
    methods: {
        async submitForm() {
            if (this.aiApi)
            {
                this.aiResponses.push({ query: this.query });
                await this.aiApi.getChatResponse({ query: this.query }).then(resp => {
                    this.aiResponses.push({ response: resp.data?.aiResponse });
                })
                    .finally(() => {
                    this.query = null;
                })
            }
        }
    },
    mounted() {
        this.aiApi = new OpenAiApiService('/api/openai/chat');
    }
}
</script>

<template>
    <form v-if="aiApi" @submit.prevent="submitForm" class="flex gap-2 items-end">
        <text-field v-model="query" required label="Input your questions here" class="w-full disabled:opacity-50" :disabled="aiApi.api.processing"/>
        <primary-button class="w-[7rem] h-fit disabled:opacity-50" :disabled="aiApi.api.processing || !query">
            <span v-show="!aiApi.api.processing && !query">
                No query
            </span>
            <span v-show="!aiApi.api.processing && !!query">
                Send
            </span>
            <span v-show="aiApi.api.processing">
                Thinking
            </span>
        </primary-button>
    </form>
    <div class="flex flex-col gap-2 backdrop-blur-sm text-cbc-dark-green drop-shadow">
        <div v-for="response in aiResponses" :class="Object.keys(response).includes('query') ? 'text-right':'text-left'">
            {{ Object.values(response).join(',') }}
        </div>
    </div>
</template>

<style scoped>

</style>
