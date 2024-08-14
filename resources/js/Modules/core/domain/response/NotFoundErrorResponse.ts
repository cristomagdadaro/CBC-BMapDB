import DtoError from "../../dto/base/DtoError";

export class NotFoundErrorResponse extends DtoError{
    constructor( response: DtoError ) {
        super(response);
        this.type = 'warning';
        this.show = true;
        this.timeout = 10000;
    }
}
