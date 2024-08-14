import IDatatableResponse from "./IDatatableResponse";

export default interface IBaseResponse {
    data: IDatatableResponse | any;
    meta: any;
    links: any;
    title: string | null;
    message: string | null;
    type: string | null;
    timeout: number | null;
    show: boolean;
}
