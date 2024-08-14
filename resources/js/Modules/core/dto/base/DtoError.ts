import IBaseError from "../../interface/base/IBaseError";

export default class DtoError implements IBaseError {
    status: number;
    message: string;
    show: boolean;
    timeout: number;
    title: string;
    type: string;
    errors?: Object;

    constructor(params: IBaseError = {
        status: null,
        message: null,
        show: true,
        timeout: 10000,
        title: null,
        type: null,
        errors: null
    }) {
        this.status = params.status;
        this.message = params.message;
        this.show = params.show;
        this.timeout = params.timeout;
        this.title = params.title;
        this.type = params.type;
        this.errors = params.errors;
    }

    static fromObject(response: IBaseError) {
        return new DtoError(response);
    }

    toObject() {
        return this;
    }
}
