import DtoError from "../../dto/base/DtoError";

export class ValidationErrorResponse extends DtoError{
    constructor( response: DtoError ) {
        super(response);
        this.type = 'warning';
        this.show = true;
        this.timeout = 10000;
    }
}
