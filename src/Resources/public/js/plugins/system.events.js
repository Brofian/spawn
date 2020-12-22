class SystemEvents extends BaseClass {

    init() {
        document.addEventListener("DOMContentLoaded", this.triggerDomLoadedEvent.bind(this), false);
        document.addEventListener("scroll", this.triggerScrollEvent.bind(this), false);
        document.addEventListener("resize", this.triggerResizeEvent.bind(this), false);
    }


    triggerDomLoadedEvent() {
        Eventmanager.triggerEvent("webu/system/DOMContentLoaded");
    }

    triggerScrollEvent() {
        Eventmanager.triggerEvent("webu/system/scroll");
    }

    triggerResizeEvent() {
        Eventmanager.triggerEvent("webu/system/resize");
    }


}
Pluginmanager.registerPlugin("webu/systemEvents", SystemEvents);