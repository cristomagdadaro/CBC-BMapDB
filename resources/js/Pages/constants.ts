// @ts-ignore
import TWGLogo from "../../../public/img/logos/biotwg.png";
// @ts-ignore
import BreedersMapLogo from "../../../public/img/breeders-map-logo.png";
import { JavascriptErrorResponse, ForbiddenErrorResponse, ValidationErrorResponse, NotFoundErrorResponse, ServerErrorResponse } from "@/Modules/core/domain/response/index";

export const ErrorResponse  = [
    ValidationErrorResponse,
    ServerErrorResponse,
    NotFoundErrorResponse,
    ForbiddenErrorResponse,
    JavascriptErrorResponse
]

export const CBCProjects = [
    {
        id: 3,
        label: 'TWG Database',
        icon: TWGLogo,
        value: 'projects.twg.index',
        show: true,
    },
    {
        id: 4,
        label: "Breeders' Map",
        icon: BreedersMapLogo,
        value: 'projects.breedersmap.index',
        show: true,
    },
]

export const CBCProjectsPublic = [
    {
        id: 1,
        label: "Plant Breeders Map",
        value: 'projects.breedersmap.public',
    },
    {
        id: 2,
        label: 'Biotech TWG Database',
        value: 'projects.twgdb.public',
    },
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
        label: "Breeders' Map",
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
