export default interface IBaseError {
    title: string;
    message: string;
    type: string;
    timeout: number;
    status: number;
    show: boolean;
    errors?: Object;
}
