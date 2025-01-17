// @ts-ignore
import TWGLogo from "../../../public/img/logos/biotwg.webp";
// @ts-ignore
import BreedersMapLogo from "../../../public/img/logos/pbmap.webp";
import { JavascriptErrorResponse, ForbiddenErrorResponse, ValidationErrorResponse, NotFoundErrorResponse, ServerErrorResponse } from "@/Modules/core/domain/response/index";

export const appName = "Plant Breeders and Innovators Network"
export const companyName = "DA-Crop Biotechnology Center"


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
        //@ts-ignore
        label: window.AppConfig.applications['TWG_DATABASE'],
        icon: TWGLogo,
        value: 'projects.twg.index',
        show: true,
    },
    {
        id: 4,
        //@ts-ignore
        label: window.AppConfig.applications['BREEDERS_MAP'],
        icon: BreedersMapLogo,
        value: 'projects.breedersmap.index',
        show: true,
    },
]

export const CBCProjectsPublic = [
    {
        id: 1,
        //@ts-ignore
        label: window.AppConfig.applications['BREEDERS_MAP'].name,
        //@ts-ignore
        route: window.AppConfig.applications['BREEDERS_MAP'].route,
        //@ts-ignore
        route_public: window.AppConfig.applications['BREEDERS_MAP'].route_public,
        //@ts-ignore
        description: window.AppConfig.applications['BREEDERS_MAP'].description,
    },
    {
        id: 2,
        //@ts-ignore
        label: window.AppConfig.applications['TWG_DATABASE'].name,
        //@ts-ignore
        route: window.AppConfig.applications['TWG_DATABASE'].route,
        //@ts-ignore
        route_public: window.AppConfig.applications['BREEDERS_MAP'].route_public,
        //@ts-ignore
        description: window.AppConfig.applications['TWG_DATABASE'].description,
    },
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
