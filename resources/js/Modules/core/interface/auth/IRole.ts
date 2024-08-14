import IPermission from "./IPermission";

export default interface IRole {
    id: number;
    name: string;
    guard_name: string;
    permissions: IPermission[];
}
