import IApplication from "../interface/auth/IApplication";
import BaseClass from "../domain/base/BaseClass";

export default class DtoApplication extends BaseClass implements IApplication {
    id: number = null;
    description: string = null;
    icon: string = null;
    name: string = null;
    status: number = null;
    url: string = null;

    constructor(dto: IApplication) {
        super();
        this.id = dto.id;
        this.name = dto.name;
        this.description = dto.description;
        this.url = dto.url;
        this.icon = dto.icon;
        this.status = dto.status;
    }


}
