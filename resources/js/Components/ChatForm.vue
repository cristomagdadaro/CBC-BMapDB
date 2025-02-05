<template>
    <div class="flex flex-col w-full h-full gap-2">
        <div class="bg-gray-200 p-5 rounded-md shadow w-full h-full">
            <div class="flex justify-between my-1 py-1">
                <h1  v-if="messages.length" class="text-lg font-bold">Messages</h1>
                <h1  v-else class="text-lg font-bold">No Messages</h1>
                <button @click="getMessages">
                    <refresh-icon class="h-auto w-6" />
                </button>
            </div>
            {{apiService.processing}}
            <ul class="flex flex-col gap-1">
                <li v-if="messages.length" class="grid grid-cols-3 text-center font-bold border border-gray-400 rounded-md">
                    <span class="bg-gray-300 p-1 px-3 rounded-r shadow  rounded-md">From</span>
                    <span class="bg-gray-300 p-1 px-3 rounded-r shadow">To</span>
                    <span class="bg-gray-300 p-1 px-3 rounded-r shadow  rounded-md">Message</span>
                </li>
                <li>
                    <div class="max-h-[30vh] overflow-hidden overflow-y-auto text-sm">
                        <div v-for="(message, index) in messages" :key="index" class="grid grid-cols-3">
                            <span class="bg-cbc-yellow-green text-dark-color p-1 px-3 rounded-l shadow">{{ (new User(message.from_user)).getFullName }}</span>
                            <span class="bg-cbc-yellow-green text-dark-color p-1 px-3 rounded-l shadow">{{ (new User(message.to_user)).getFullName }}</span>
                            <span class="bg-gray-300 p-1 px-3 rounded-r shadow">{{ message.message }}</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <form @submit.prevent="sendMessage" class="flex flex-col gap-2 p-5 w-full bg-gray-200 rounded-md shadow-md">
            <div class="grid grid-cols-2 gap-2">
                <select-search-field :api-link="route('api.users.index.public')" label="Send to" v-model="userId" />
                <text-field v-model="newMessage" label="Type a message" />
            </div>
            <button :disabled="notReadyToSend" type="submit" class="rounded disabled:opacity-25 p-2 bg-cbc-dark-green text-white">Send</button>
        </form>
    </div>
</template>

<script setup>
import {ref, defineEmits, computed, onMounted} from "vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import TextField from "@/Components/Form/TextField.vue";
import User from "@/Modules/core/domain/auth/User";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import Message from "@/Pages/Message/domain/Message";
import RefreshIcon from "@/Components/Icons/RefreshIcon.vue";

const newMessage = ref("");
const messages = ref([]);
const userId = ref("");
const emit = defineEmits(["messageSent"]);

const notReadyToSend = computed(() => newMessage.value.trim() === "" || !userId.value);
const apiService =  new ApiService(route('api.message.index'));

async function sendMessage(){
    if (!notReadyToSend) return;

    apiService.baseUrl = "/chats/send";
    const messageData = { message: newMessage.value, to_user: userId.value };

    await apiService.post(messageData).then( async (response) => {
        emit("messageSent", response.data);
        await getMessages();
    });

    newMessage.value = "";
    userId.value = null;
}

async function getMessages() {
    apiService.baseUrl = route('api.message.index');
    await apiService.get({
        sort:'created_at',
        order:'desc',
    }, Message).then((resp) => {
        messages.value = resp?.data || [];
        console.log(resp);
    });
}

onMounted(async () => {
    await getMessages();
})

</script>
