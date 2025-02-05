<template>
    <div class="bg-cbc-dark-green p-5 rounded-md shadow w-full h-full">
        <ul class="flex flex-col gap-1 h-full">
            <li class="grid grid-cols-1 text-center font-bold border border-gray-400 rounded-md">
                <div class="bg-gray-300 p-1 px-3 rounded-r shadow  rounded-md">
                    <h1  v-if="conversations.length" class="text-lg font-bold">Conversations</h1>
                    <h1  v-else class="text-lg font-bold">No Conversations</h1>
                </div>
            </li>
            <li>
                <div class="max-h-[40vh] overflow-hidden overflow-y-auto text-sm bg-white">
                    <div v-for="(message, index) in conversations" :key="index" class="grid grid-cols-1 hover:bg-cbc-yellow ">
                        <button class="hover:text-gray-800 hover:shadow-none active:scale-95 duration-100 text-dark-color p-3 rounded-l shadow flex flex-col leading-[0.8rem]">
                            <span class="text-normal font-semibold px-3">
                                {{ (new User(message.from_user)).getFullName }}
                            </span>
                            <span class="text-xs font-light px-3">
                                Last {{ message.latest_created_at }}
                            </span>
                        </button>
                    </div>
                    <div v-if="conversations.length" class="grid grid-cols-1 p-2 text-xs text-center bg-gray-300">
                        Bottom
                    </div>
                </div>
            </li>
        </ul>
    </div>

</template>

<script setup>
import {onMounted, ref} from "vue";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import User from "../Modules/core/domain/auth/User";

const conversations = ref([]);

async function getConversations() {
    const apiService =  new ApiService(route('api.message.conversations'));
    return await apiService.get({
        page:1,
        per_page:10,
        sort:'id',
        order:'desc'
    }, null);
}

 onMounted(async () => {
     conversations.value = (await getConversations())?.data || [];
})
</script>
