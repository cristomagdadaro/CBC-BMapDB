<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import NewUserTable from "@/Pages/Admin/components/NewUser/NewUserTable.vue";
import Tab from "@/Components/Tab/Tab.vue";
import AppAccountsTable from "@/Pages/Admin/components/Accounts/AppAccountsTable.vue";
import ApplicationsTable from "@/Pages/Admin/components/Applications/ApplicationsTable.vue";

export default {
    name: "Administrator",
    components: {ApplicationsTable, AppAccountsTable, Tab, NewUserTable, AppLayout },
    data() {
        return {
            tabs: [
                {
                    name: "tab1",
                    label: "Users",
                    active: true,
                    route: { name: 'administrator.users' },
                },{
                    name: "tab2",
                    label: "Accounts Approval",
                    active: false,
                    route: { name: 'administrator.approved-accounts' },
                },{
                    name: "tab3",
                    label: "System",
                    active: false,
                    route: { name: 'administrator.applications' },
                },
            ],
        }
    },
}
</script>

<template>
    <AppLayout title="Admin Panel">
        <div>
            <h1 class="h1 p-2 bg-white text-center font-semibold uppercase select-none">Administrative Panel</h1>
            <Tab :tabs="tabs" v-if="$page.props.auth.user">
                <template #tab1>
                    <new-user-table />
                </template>
                <template #tab2>
                    <app-accounts-table />
                </template>
                <template #tab3>
                    <applications-table />
                </template>
            </Tab>
            <p v-else>Please login to view the data</p>
        </div>

    </AppLayout>
</template>

<style scoped>

</style>
