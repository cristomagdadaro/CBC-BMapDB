<script>
import TextField from "@/Components/Form/TextField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import OpenAiApiService from "@/Pages/OpenAi/infrastructure/OpenAiApiService";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import { nextTick } from "vue";

export default {
    name: "AiChat",
    components: {TransitionContainer, PrimaryButton, TextField},
    data() {
        return {
            query: null,
            aiApi: null,
            aiResponses: [],
            isOpen: false,
        }
    },
    methods: {
        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => this.scrollToBottom());
            }
        },
        async scrollToBottom() {
            await nextTick();
            const container = this.$refs.chatMessages;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },
        async submitForm() {
            if (!this.aiApi || !this.query?.trim()) {
                return;
            }

            const prompt = this.query.trim();
            this.aiResponses.push({ type: 'query', text: prompt });
            this.query = null;
            await this.scrollToBottom();

            await this.aiApi.getChatResponse({ query: prompt })
                .then(resp => {
                    this.aiResponses.push({
                        type: 'response',
                        text: resp.data?.aiResponse || 'I could not generate a response right now. Please try again.'
                    });
                })
                .finally(async () => {
                    await this.scrollToBottom();
                });
        }
    },
    mounted() {
        this.aiApi = new OpenAiApiService('/api/openai/chat');
    }
}
</script>

<template>
    <div v-if="aiApi" class="fixed bottom-6 right-6 z-50">
        <button
            type="button"
            @click="toggleChat"
            class="w-14 h-14 rounded-full bg-pin-green text-white shadow-lg hover:bg-pin-green-dark hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 flex items-center justify-center focus-ring"
            aria-label="Toggle AI chatbot"
        >
            <span v-if="!isOpen" class="text-xl leading-none">💬</span>
            <span v-else class="text-xl leading-none">×</span>
        </button>

        <transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-2 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-2 scale-95"
        >
            <div
                v-if="isOpen"
                class="absolute bottom-16 right-0 w-[22rem] sm:w-[24rem] h-[30rem] bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col"
            >
                <div class="bg-pin-green text-white px-4 py-3 flex items-center justify-between">
                    <div class="leading-tight">
                        <div class="font-semibold">Biotech Assistant</div>
                        <div class="text-xs text-white/80">OpenAI powered chat</div>
                    </div>
                    <span
                        class="w-2 h-2 rounded-full bg-pin-lime"
                        aria-hidden="true"
                    />
                </div>

                <div ref="chatMessages" class="flex-1 overflow-y-auto p-4 bg-pin-gray space-y-3">
                    <div v-if="!aiResponses.length" class="text-sm text-gray-500 bg-white rounded-xl px-3 py-2 shadow-sm max-w-[85%]">
                        Ask anything about biotechnology.
                    </div>

                    <div
                        v-for="(message, index) in aiResponses"
                        :key="`${message.type}-${index}`"
                        class="flex"
                        :class="message.type === 'query' ? 'justify-end' : 'justify-start'"
                    >
                        <div
                            class="max-w-[85%] px-3 py-2 rounded-2xl text-sm leading-relaxed whitespace-pre-line shadow-sm"
                            :class="message.type === 'query'
                                ? 'bg-pin-green text-white rounded-br-md'
                                : 'bg-white text-gray-700 rounded-bl-md border border-gray-200'"
                        >
                            {{ message.text }}
                        </div>
                    </div>

                    <div v-if="aiApi.api.processing" class="flex justify-start">
                        <div class="bg-white text-gray-600 rounded-2xl rounded-bl-md border border-gray-200 px-3 py-2 text-sm shadow-sm">
                            <transition-container type="slide-left">
                                <span class="inline-flex gap-1 items-center">
                                    <span v-for="i in [1,2,3]" :key="`typing-${i}`" class="w-1.5 h-1.5 rounded-full bg-pin-green/70" />
                                </span>
                            </transition-container>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submitForm" class="p-3 border-t border-gray-200 bg-white">
                    <div class="flex items-center gap-2">
                        <text-field
                            v-model="query"
                            class="w-full disabled:opacity-50"
                            :disabled="aiApi.api.processing"
                            placeholder="Type your message..."
                        />
                        <primary-button
                            class="min-w-[5rem] disabled:opacity-50"
                            :disabled="aiApi.api.processing || !query"
                        >
                            <span v-show="!aiApi.api.processing">Send</span>
                            <span v-show="aiApi.api.processing">...</span>
                        </primary-button>
                    </div>
                    <div class="mt-2 text-[11px] text-gray-500">
                        Experimental feature. Responses may be inaccurate.
                    </div>
                </form>
            </div>
        </transition>
    </div>
</template>

<style scoped>

</style>
