import DtoBaseClass from "../../dto/base/DtoBaseClass";
import IBaseClass from "../../interface/base/IBaseClass";

export default class BaseClass extends DtoBaseClass implements IBaseClass
{
    constructor(resp = {}) {
        super(resp);
    }
}
