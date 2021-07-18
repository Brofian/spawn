export default class EventManagerSubject {

    eventList = [];

    /**
     * @param event string
     * @param args array
     */
    publish(event, args = []) {
        for(let eventListenerObject of this.eventList) {
            if(eventListenerObject.eventName === event) {
                eventListenerObject.callable(args);
            }
        }
    }

    /**
     * @param event string
     * @param callable callable
     */
    subscribe(event, callable) {
        let eventListenerObject = {
            eventName: event,
            callable: callable
        };
        this.eventList.push(eventListenerObject);
    }

    /**
     * @param event string
     * @param callable callable
     */
    unsubscribe(event, callable) {
        let eventListenerObject = {
            eventName: event,
            callable: callable
        };

        let i = this.eventList.indexOf(eventListenerObject);
        this.eventList.splice(i, 1);
    }




}