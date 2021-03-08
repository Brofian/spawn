/* info:
 Triggers the event "dom.content.ready" and adds the class "is-ready" to the body, when dom is loaded
 */

import Plugin from 'Plugin';

export default class DomLoadedPlugin extends Plugin {

    init() {
        var me = this;


        me.expectedReadyState = "complete";
        me.event = "dom.content.ready";
        me.readyClass = "is-ready";

        me.checkDomReadyState();
    }


    checkDomReadyState() {
        var me = this;

        if(document.readyState === me.expectedReadyState) {
            me.addReadyClass();
        }
        else {
            document.addEventListener('readystatechange', me.addReadyClass.bind(me));
        }
    }


    addReadyClass() {
        var me = this;

        if(document.readyState !== me.expectedReadyState) return false;


        $(document).trigger(me.event);
        document.querySelector("body").classList.add(me.readyClass);
    }


}