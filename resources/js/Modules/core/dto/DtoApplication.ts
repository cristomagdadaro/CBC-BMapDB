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
        this.table = 'applications';
        this.id = dto.id;
        this.name = dto.name;
        this.description = dto.description;
        this.url = dto.url;
        this.icon = dto.icon;
        this.status = dto.status;
    }

    get appTabs() {
        switch (this.name) {
            // @ts-ignore
            case window.AppConfig.applications['BREEDERS_MAP'].name:
                return [
                    {
                        name: 'projects.breedersmap.breeder',
                        label: 'Breeders',
                    },
                    {
                        name: 'projects.breedersmap.commodity',
                        label: 'Commodities',
                    },
                    {
                        name: 'projects.breedersmap.geomap',
                        label: 'Geo Map',
                    },
                ];
            // @ts-ignore
            case window.AppConfig.applications['TWG_DATABASE'].name:
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
