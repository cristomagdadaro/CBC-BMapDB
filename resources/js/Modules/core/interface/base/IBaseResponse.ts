import IDatatableResponse from "./IDatatableResponse";

export default interface IBaseResponse {
    data: IDatatableResponse | any;
    meta: any;
    links: any;
}
