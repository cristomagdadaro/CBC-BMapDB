export default {
    computed: {
        permissions() {
            return this.$page.props.permissions;
        }
    },
    methods: {
        hasPermission(permission) {
            if (!permission) return false;
            return this.permissions.includes(permission);
        }
    }
}
