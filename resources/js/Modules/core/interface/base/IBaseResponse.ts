import IDatatableResponse from "./IDatatableResponse";
import Notification from "@/Components/Modal/Notification/Notification";
export default interface IBaseResponse {
    data: IDatatableResponse | any;
    meta?: any;
    links?: any;
}
