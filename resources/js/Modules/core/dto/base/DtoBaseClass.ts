import IBaseClass from "../../interface/base/IBaseClass";
import BaseClass from "../../domain/base/BaseClass";

export default class DtoBaseClass extends Object implements IBaseClass {
    table?: string = null;

    constructor(dto = {}) {
        super();
        //@ts-ignore
        Object.assign(this, dto);
    }

    get getFullName() {
        // check if the instance has a fname, mname, etc. attribute
        if (this.hasOwnProperty('fname') && this.hasOwnProperty('mname') && this.hasOwnProperty('lname') && this.hasOwnProperty('suffix')){
            //@ts-ignore
            return [this.fname, this.mname, this.lname, this.suffix]
                .filter(part => part)
                .join(" ");
        }

        //@ts-ignore
        return this.lable || this.title || this.name;
    }

    /**
     * Get table name
     * @returns {string}
     */
    static getTable(): string {
        return null;
    }

    /**
     * Convert object to DTO
     * @param obj
     * @returns {Object}
     */
    static toObject(obj: Object): Object {
        // @ts-ignore
        return Object.assign({}, obj);
    }

    /**
     * Convert array of objects to formatted array of options for dropdown select fields
     * @param options
     * @returns {Array}
     */
    static makeAsOptions(options: any[]): any[] {
        return options.map((option) => {
            return {
                value: option.id || option.value,
                label: option.name || option.title || option.label || option.value || option.id
            };
        });
    }

    /**
     * Get columns for table
     * @returns {Array}
     * @static
     */
    static getColumns(): any[] {
        return [];
    }

    /**
     * Get visible columns for table
     */
    static visibleColumns(): any[] {
        return this.getColumns().filter((column) => column.visible);
    }

    /**
     * Get nested value
     * @param obj
     * @param path
     * @returns {any}
     */
    static getNestedValue(obj: Object, path: string): string {
        return path.split('.').reduce((acc, part) => acc && acc[part], obj);
    }

    static createForm()
    {
        return {};
    }

    static updateForm(oldValue: Partial<BaseClass>)
    {
        return {};
    }
}
