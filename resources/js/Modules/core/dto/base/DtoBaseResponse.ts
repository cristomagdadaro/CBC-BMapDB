import IBaseResponse from "../../interface/base/IBaseResponse";
import Notification from "@/Components/Modal/Notification/Notification";
export default class DtoBaseResponse implements IBaseResponse {
    data: any;
    links: any;
    meta: any;

    notification?: Notification;

    constructor(params: IBaseResponse) {
        if (!params) return;
        this.data = params.data.data ?? null;
        this.links = params.data.links ?? null;
        this.meta = params.data.meta ?? null;
        this.notification = params.data ? new Notification(params.data) : null;
    }

    getMeta() {
        return this.meta
    }

    getData() {
        return this.data
    }

    getLinks() {
        return this.links
    }

    getNotification() {
        return this.notification
    }

    getCurrentPage() {
        if(this.meta)
            return this.meta.current_page
        return null
    }

    getLastPage() {
        if(this.links)
            return this.links.last
        return null
    }

    getFirstPage() {
        if(this.links)
            return this.links.first
        return null
    }

    getNextPage() {
        if(this.links)
            return this.links.next
        return null
    }

    getPrevPage() {
        if(this.links)
            return this.links.prev
        return null
    }

    getTotalPage() {
        if(this.meta)
            return this.meta.total
        return null
    }

    getPerPage() {
        if(this.meta)
            return this.meta.per_page
        return null
    }

    getFromPage() {
        if(this.meta)
            return this.meta.from
        return null
    }

    getToPage() {
        if(this.meta)
            return this.meta.to
        return null
    }

    isAllFetched() {
        return this.getCurrentPage() && this.getTotalPage() && this.getCurrentPage() === this.getTotalPage()
    }
}
