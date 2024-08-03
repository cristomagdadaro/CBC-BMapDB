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

    get appTabs() {
        switch (this.name) {
            case "Breeder's Map":
                return [
                    {
                        name: 'projects.breedersmap.breeder',
                        label: 'Breeders',
                    },
                    {
                        name: 'projects.breedersmap.commodity',
                        label: 'Commoditie',
                    },
                    {
                        name: 'projects.breedersmap.geomap',
                        label: 'Geo Map',
                    },
                ];
            case "TWG Database":
                return [
                    {
                        name: 'projects.twg.summary',
                        label: 'Summary',
                    },
                    {
                        name: 'projects.twg.experts',
                        label: 'Experts',
                    },
                    {
                        name: 'projects.twg.products',
                        label: 'Products',
                    },
                    {
                        name: 'projects.twg.projects',
                        label: 'Projects',
                    },
                    {
                        name: 'projects.twg.services',
                        label: 'Services',
                    },
                ];
            default:
                return
        }
    }

}
