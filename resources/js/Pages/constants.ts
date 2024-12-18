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
        description: "Centralized repository for crop biotechnology commodities, providing essential data and resources in one accessible platform. It includes comprehensive information on germplasm, genetic traits, NSIC registrations, plant variety protections, and GM regulatory approvals. This tool empowers breeders and researchers to drive innovation, improve crop productivity, and promote sustainable agriculture."
    },
    {
        id: 2,
        label: 'Biotech TWG Database',
        value: 'projects.twgdb.public',
        description: "A robust system designed to manage and organize biotechnology-related projects efficiently. It serves as a centralized hub for storing, tracking, and accessing essential data from various technical working groups. This database enhances collaboration, streamlines project monitoring, and supports informed decision-making in the field of biotechnology."
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
