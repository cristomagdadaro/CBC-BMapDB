import DtoUser from "../../dto/DtoUser";
import { usePage } from "@inertiajs/vue3";

export default class User extends DtoUser {
    constructor(user: DtoUser) {
        super(user);
    }
}
