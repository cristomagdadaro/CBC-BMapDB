import DtoUser from "../../dto/DtoUser";

export default class User extends DtoUser {
    constructor(user: DtoUser) {
        super(user);
    }

    get affiliation() {
        if (this.affiliated)
            return this.affiliated.name;
        return 'Unknown';
    }

    get isAdmin() {
        return this.roles.some(role => role.name === "Administrator");
    }

    get accountsCount(): number {
        if (this.accounts)
            return this.accounts.length;
        return 0;
    }

    get accountsList(): string[] {
        return this.accounts.map(account => {
            if (account.application)
                return account.application.name;
            return null;
        });
    }
}
