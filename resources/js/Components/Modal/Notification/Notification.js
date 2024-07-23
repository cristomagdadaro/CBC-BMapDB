import {BaseClass} from "@/Modules/core/domain/BaseClass.js";
import {ref} from "vue";

export default class Notification extends BaseClass {
    static notifications = ref([]);

    constructor(param = {
                    id: 0,
                    title: 'Warning',
                    message: 'Unknown Error',
                    type: 'failed',
                    timeout: 3000,
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

        setTimeout(() => {
            this.close();
        }, this.timeout);
    }

    static pushNotification( param = {
        id: 0,
        title: 'Warning',
        message: 'Unknown Error',
        type: 'failed',
        timeout: 3000,
        show: true
    }){
        new this.prototype.constructor(param);

        if (!param.timeout)
            return;

        setTimeout(() => {
            this.notifications.value.shift();
        }, param.timeout);
    }

    close(){
        Notification.notifications.value.splice(Notification.notifications.value.indexOf(this), 1);
    }
}
