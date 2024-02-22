// @ts-ignore
import TWGLogo from "../../../public/img/twg-db-logo.png";
// @ts-ignore
import BreedersMapLogo from "../../../public/img/breeders-map-logo.png";
export const CBCProjects = [
    {
        id: 1,
        label: 'TWG Database',
        icon: TWGLogo,
        value: 'projects.twg.index',
    },
    {
        id: 2,
        label: "Breeder's Map",
        icon: BreedersMapLogo,
        value: 'projects.breedersmap.index',
    },
    {
        id: 3,
        label: "Inventory MS",
        value: 'services.inventory.index',
    }
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
export const EducLevel = [
    {
        id: 1,
        value: 'Bachelor\'s',
        label: 'Bachelor\'s',
    },
    {
        id: 2,
        value: 'Master\'s',
        label: 'Master\'s',
    },
    {
        id: 3,
        value: 'Doctoral',
        label: 'Doctoral',
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
