// @ts-ignore
import TWGLogo from "../../../public/img/twg-db-logo.png";
// @ts-ignore
import BreedersMapLogo from "../../../public/img/breeders-map-logo.png";
import { ValidationErrorResponse, NotFoundErrorResponse, ServerErrorResponse } from "@/Modules/core/infrastructure/index";

export const ErrorResponse  = [
    ValidationErrorResponse,
    ServerErrorResponse,
    NotFoundErrorResponse,
]

export const CBCProjects = [
    {
        id: 1,
        label: 'Dashboard',
        icon: null,
        value: 'dashboard',
    },
    {
        id: 2,
        label: 'Administrator',
        icon: null,
        value: 'administrator.index',
    },
    {
        id: 3,
        label: 'TWG Database',
        icon: TWGLogo,
        value: 'projects.twg.index',
    },
    {
        id: 4,
        label: "Breeder's Map",
        icon: BreedersMapLogo,
        value: 'projects.breedersmap.index',
    },
]

export const CBCProjectsPublic = [
    {
        id: 1,
        label: 'TWG Database',
        value: 'projects.twgdb.public',
    },
    {
        id: 2,
        label: "Breeder's Map",
        value: 'projects.breedersmap.public',
    }
]

/*used in registration dropdown*/
export const Projects = [
    {
        id: 1,
        value: 'projects.twg.index', // router name of the project
        label: 'TWG Database',
    },{
        id: 2,
        value: 'projects.breedersmap.index',
        label: 'Breeders Map',
    }
]

export const ProjectStatus = [
    {
        id: 1,
        value: 'Active',
        label: 'Active',
    },{
        id: 2,
        value: 'Completed',
        label: 'Completed',
    },{
        id: 3,
        value: 'Cancelled',
        label: 'Cancelled',
    },{
        id: 4,
        value: 'On Hold',
        label: 'On Hold',
    }
]

export const Permission = Object.freeze({
    CREATE: 'create',
    VIEW: 'view',
    UPDATE: 'update',
    DELETE: 'delete',
});
