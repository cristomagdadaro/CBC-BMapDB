import ApiService from "@/Modules/core/infrastructure/ApiService";

export default {
    data() {
        return {
            filteredRoles: null, // To hold the roles specific to the selected application
            selectedApplication: null, // To track the currently selected application.
            applicationRolesMap: {
                "1": ['TWG Admin', 'Researcher'], // TWG Db
                "2": ['Focal Person','Researcher', 'Breeder'], // Plant Breeders Map
            },
            applications: [],
            roles:[],
            form: {},
        }
    },
    methods: {
        async getListOfApplications() {
            const api = new ApiService(route('api.applications.index.public'));
            await api.get().then(response => {
                this.applications = response.data;
            });
        },
        async getListOfRoles() {
            const api = new ApiService(route('api.roles.index.public'));
            await api.get().then(response => {
                this.roles = response.data;
                this.filterRolesByApplication(); // Filter roles after fetching them
            });
        },
        filterRolesByApplication() {
            // Function to filter roles based on the selected application
            const allowedRoles = this.applicationRolesMap[this.selectedApplication] || [];
            this.filteredRoles = this.roles.filter(role =>
                allowedRoles.includes(role.label) // Assuming roles have a 'name' property
            );
        }
    },
    async mounted() {
        await this.getListOfApplications();
        await this.getListOfRoles();

        this.applications =  this.applications.map((application) => {
            return {
                id: application.id,
                value: application.id,
                label: application.name,
            };
        });

        this.roles = this.roles.map((role) => {
            return {
                id: role.id,
                value: role.id,
                label: role.name,
                permissions: role.permissions,
            };
        });
    }
}
