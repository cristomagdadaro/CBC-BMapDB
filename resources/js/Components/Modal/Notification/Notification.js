import {BaseClass} from "@/Modules/core/domain/BaseClass.js";
import {ref} from "vue";

export default class Notification extends BaseClass {
    static notifications = ref([]);

    constructor(notify = {
        id: 0,
        title: '',
        message: '',
        type: 'failed',
        timeout: 5000,
        show: true,
    }) {
        super(notify);
        this.id = Notification.notifications.value.length + 1;
        this.title = notify.title;
        this.message = notify.message;
        this.type = notify.type;
        this.timeout = notify.timeout;
        this.show = notify.show;
    }

    static pushNotification(notification = {
        id: 0,
        title: '',
        message: '',
        type: 'failed',
        timeout: 5000,
        show: true,
    }){
        this.notifications.value.push(new this.prototype.constructor(notification));

        if (!notification.timeout)
            return;

        setTimeout(() => {
            this.notifications.value.shift();
        }, notification.timeout);
    }

    close(){
        Notification.notifications.value.splice(Notification.notifications.value.indexOf(this), 1);
    }
}
