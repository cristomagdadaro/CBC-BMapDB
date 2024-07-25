import DtoAccount from "../../dto/DtoAccount";

export default class Account extends DtoAccount {
    constructor(account: DtoAccount) {
        super(account);
    }

    get applicationName() {
        return this.application.name;
    }
}
