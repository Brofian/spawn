import EventManagerSubject from "./EventManagerSubject";

export const EventManagerInstance = new EventManagerSubject();

export default class EventManager {

    static publish(event, args = []) {
        return EventManagerInstance.publish(event, args);
    }

    static subscribe(event, callable) {
        return EventManagerInstance.subscribe(event, callable);
    }

    static unsubscribe(event, callable) {
        return EventManagerInstance.unsubscribe(event, callable);
    }

}
