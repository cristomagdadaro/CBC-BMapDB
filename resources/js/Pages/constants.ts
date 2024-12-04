// @ts-ignore
import TWGLogo from "../../../public/img/logos/biotwg.png";
// @ts-ignore
import BreedersMapLogo from "../../../public/img/logos/pbmap-sm.png";
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
        label: 'Biotech TWG Database',
        icon: TWGLogo,
        value: 'projects.twg.index',
        show: true,
    },
    {
        id: 4,
        label: "Plant Breeders Map",
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
        description: "Centralized repository for crop biotechnology commodities"
    },
    {
        id: 2,
        label: 'Biotech TWG Database',
        value: 'projects.twgdb.public',
        description: "A robust database system for biotechnology related projects"
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
