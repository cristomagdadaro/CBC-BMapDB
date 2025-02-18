<script>
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import OpenAiApiService from "@/Pages/OpenAi/infrastructure/OpenAiApiService";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

export default {
    name: "AiChat",
    components: {TransitionContainer, PrimaryButton, TextField, BaseCreateForm},
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

    <div v-if="aiApi" class="flex flex-col gap-5">
        <div class="flex items-center justify-center">
            <div class="flex flex-col w-fit justify-center items-center leading-none relative">
                <div class="font-bold text-subtitle w-fit">Ask anything about biotechnology</div>
                <div class="w-full text-end text-xs">OpenAi powered Chatbox</div>
            </div>
        </div>
        <form @submit.prevent="submitForm" class="flex flex-col leading-none">
            <div class="flex w-full gap-1 drop-shadow">
                <text-field v-model="query" class="w-full disabled:opacity-50" :disabled="aiApi.api.processing"/>
                <primary-button class="w-[7rem] disabled:opacity-50" :disabled="aiApi.api.processing || !query">
                <span v-show="!aiApi.api.processing && !query">
                    No prompt
                </span>
                    <span v-show="!aiApi.api.processing && !!query">
                    Send
                </span>
                    <span v-show="aiApi.api.processing">
                    Thinking
                </span>
                </primary-button>
            </div>
            <div class="w-full text-start text-xs text-gray-500 mt-2">Disclaimer: This is still an experimental feature. Responses maybe inaccurate.</div>
        </form>
        <div class="flex flex-col-reverse gap-2 backdrop-blur-sm text-cbc-dark-green drop-shadow max-h-[20rem] overflow-y-auto">
            <div v-for="response in aiResponses" :class="Object.keys(response).includes('query') ? 'text-right text-blue-900':'text-left'" class="drop-shadow">
                {{ Object.values(response).join(',') }}
            </div>
            <transition-container type="slide-left">
                <span v-show="aiApi.api.processing">
                        <span v-for="i in [0,1,2,3,4,5]" v-show="true" class="duration-500">.</span>
                </span>
            </transition-container>
        </div>
    </div>
</template>

<style scoped>

</style>
