<script setup>
import Body from '@/Pages/Body.vue';
import BackToTop from "@/Components/BackToTop.vue";
import NotifBanner from "@/Components/Modal/Notification/NotifBanner.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import {ref} from "vue";
const props = defineProps({
    isWideDisplay: {
        type: Boolean,
        default: true,
    }
});

const closePropsDiv = ref(false);

function getKeyValuePairs(obj, prefix = '') {
    let result = [];

    for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
            let fullKey = prefix ? `${prefix}.${key}` : key;
            if (typeof obj[key] === 'object' && obj[key] !== null) {
                result = result.concat(getKeyValuePairs(obj[key], fullKey));
            } else {
                result.push({ key: fullKey, value: obj[key] });
            }
        }
    }

    return result;
}

</script>
<template>
    <div :class="closePropsDiv? 'h-fit max-h-screen justify-between p-5':'h-12 justify-end items-center'" class="overflow-y-auto fixed bottom-0 z-[999] bg-gray-100 flex w-full opacity-90 text-gray-900">
        <table v-if="closePropsDiv">
            <tr>
                <th class="px-5 border border-gray-900">Key</th>
                <th class="px-5 border border-gray-900">Value</th>
            </tr>
            <tr v-for="pair in getKeyValuePairs($page.props)">
                <td class="px-5 border border-gray-900">{{ pair.key }}</td>
                <td class="px-5 border border-gray-900">{{ pair.value }}</td>
            </tr>
        </table>
        <close-icon class="h-7 w-7" @click="closePropsDiv = !closePropsDiv" />
    </div>
    <Body :is-wide-display="props.isWideDisplay">
    <NotifBanner />
    <slot />
    <back-to-top />
    </Body>
</template>
