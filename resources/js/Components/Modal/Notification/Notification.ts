import {ref} from "vue";
import BaseClass from "@/Modules/core/domain/base/BaseClass";
import INotification from "./INotification";

export default class Notification extends BaseClass implements INotification{
    id: number;
    message: string;
    show: boolean;
    timeout: number;
    title: string;
    type: string;

    static notifications = ref([]);

    constructor(param: INotification = {
        id: 0,
        title: 'Unknown',
        message: 'No Message',
        type: 'warning',
        timeout: 10000,
        show: true
    }) {
        super();
        this.id = Notification.notifications.value.length + 1;
        this.title = param.title;
        this.message = param.message;
        this.type = param.type;
        this.timeout = param.timeout;
        this.show = param.show;

        if (this.show) {
            Notification.notifications.value.push(this);
        }

        this.close(this.timeout);
    }

    static pushNotification( param = {
        id: 0,
        title: 'Unknown',
        message: 'No Message',
        type: 'warning',
        timeout: 10000,
        show: true
    }){
        new this(param);

        if (!param.timeout)
            return;

        setTimeout(() => {
            this.notifications.value.shift();
        }, param.timeout);
    }

    close(constant: number){
        setTimeout(() => {
            Notification.notifications.value.splice(Notification.notifications.value.indexOf(this), 1);
        }, constant ?? 5000);
    }
}
