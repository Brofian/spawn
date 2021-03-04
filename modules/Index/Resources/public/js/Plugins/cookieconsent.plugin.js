import Plugin from "Plugin";

export default class CookieConsentPlugin extends Plugin {



    init() {
        var me = this;

        me.buttonSelector = ".cookie-consent-button";
        me.acceptedClass = "accepted";
        me.cookie = "acceptedCookies";

        me.addEventListeners();
    }

    addEventListeners() {
        var me = this;

        var button = me._element.querySelector(me.buttonSelector);

        console.log(me);
        console.log(button);

        if(button) {
            button.addEventListener('click', me.onAccept.bind(me));
        }

    }


    onAccept() {
        var me = this;

        document.cookie = me.cookie+'=true';
        me._element.classList.add(me.acceptedClass)
    }
}