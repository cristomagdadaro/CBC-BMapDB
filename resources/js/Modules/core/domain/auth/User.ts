import DtoUser from "../../dto/DtoUser";

export default class User extends DtoUser {
    constructor(user: DtoUser) {
        super(user);
    }

    get isAdmin() {
        return this.roles.some(role => role.name === "Administrator");
    }

    get getRole() {
        return this.roles.map(role => role.name).join(", ");
    }

    get accountsCount(): number {
        if (this.accounts)
            return this.accounts.length;
        return 0;
    }

    get accountsNames(): string {
        if (this.accounts)
            return this.accounts.map(account => {
                if (account.application)
                    return account.application.name;
                return null;
            }).join(', ');
        return null;
    }

    static getHiddenColumns() {
        return ['id','updated_at', 'created_at', 'deleted_at'];
    }
}
