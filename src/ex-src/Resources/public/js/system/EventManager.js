class EventManager {

    eventList = [];

    subscribeEvent(event, namespace, callable) {
        event = event.toString();
        namespace = namespace.toString();



        this.eventList.push([
            event,
            namespace,
            callable
        ]);

    }

    unsubscribeEvent(event, namespace) {
        event = event.toString();
        namespace = namespace.toString();
        var newEventList = [];

        for(let eventItem of this.eventList) {
            if(eventItem[0] !== event && eventItem[1] !== namespace) {
                newEventList.push(eventItem);
            }
        }

        this.eventList = newEventList;
    }


    triggerEvent(event, params = null) {
        for(let eventItem of this.eventList) {
            if(eventItem[0] === event) {
                eventItem[2](params);
            }
        }
    }

}

Eventmanager = new EventManager();
window.Eventmanager = Eventmanager;

