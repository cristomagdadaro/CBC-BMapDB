import DtoError from "../../dto/base/DtoError";

export class UnknownErrorResponse extends DtoError{
    constructor( response: DtoError ) {
        super(response);
        this.type = 'failed';
        this.show = true;
        this.timeout = 10000;
    }
}