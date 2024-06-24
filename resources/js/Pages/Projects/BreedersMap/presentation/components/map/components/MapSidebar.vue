<template>
    <div class="info-sidebar" :class="{ 'hidden': !visible }">
        <button @click="closeSidebar" class="close-btn">Close</button>
        <div v-if="point">
            <h2>{{ point.city }}</h2>
            <table>
                <tr v-for="(value, key) in point" :key="key">
                    <th>{{ formatName(key) }}</th>
                    <td>{{ value }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        point: Object,
        visible: Boolean
    },
    methods: {
        closeSidebar() {
            this.$emit('close');
        },
        formatName(name) {
            return name.replace(/_/g, ' ').toUpperCase();
        }
    }
}
</script>

<style scoped>
.info-sidebar {
    position: absolute;
    top: 0;
    right: 0;
    width: 300px;
    height: 100%;
    background: white;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
    overflow-y: auto;
    padding: 20px;
}
.hidden {
    display: none;
}
.close-btn {
    background: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}
</style>
