import Plugin from "Plugin";

export default class ConactFormPlugin extends Plugin {

    outputElementSelector = ".contact-form-message";
    messageContainerSelector = ".message-content";

    sendingProblemMessage = "Es ist ein Fehler bei der Übermittlung aufgetreten. Bitte prüfe deine Internetverbindnug und versuche es später nochmal";
    serverErrorMessage = "Deine Anfrage konnte gerade leider nicht verarbeitet werden. Bitte versuche es später nochmal oder sende eine Email an <a href='mail:holzwarth.fabian@freenet.de'>holzwarth.fabian@freenet.de</a>";
    missingValueMessage = "Die Anfrage war leider unvollständig. Bitte fülle alle Felder aus und versuche es nochmal";
    invalidEmailMessage = "Deine Email-Adresse ist leider ungültig! Bitte prüfe die Eingabe und versuche es nochmal";



    activeClass = "visible";

    init() {
        var me = this;

        me.registerEventListeners();
    }

    registerEventListeners() {
        var me = this;

        me._$element.on("ajaxform:success", me.onSuccess.bind(me));
        me._$element.on("ajaxform:fail", me.onFail.bind(me));
    }


    onSuccess(event, result) {
        var me = this;

        var parsed_result = JSON.parse(result);
        if(parsed_result["success"] && parsed_result["success"]==="true") {
            //some success event

            //set cookie to prevent another sending
            var d = new Date();
            d.setTime(d.getTime() + (60*60*1000));
            document.cookie = "hasSendContactForm=true; expires="+d.toUTCString();

            me._element.classList.add("successfully-submitted");

        }
        else {
            var message = "general_error";
            if(parsed_result["problem"]) {
                message = parsed_result["problem"];
            }


            var outputElement = me._element.querySelector(me.outputElementSelector);
            var messageElement = outputElement.querySelector(me.messageContainerSelector);

            switch(message) {
                case "general_error":
                    messageElement.innerHTML = me.serverErrorMessage;
                    break;
                case "invalid_email":
                    messageElement.innerHTML = me.invalidEmailMessage;
                    break;
                case "missing_value":
                    messageElement.innerHTML = me.missingValueMessage;
                    break;
            }

            outputElement.classList.add(me.activeClass);
        }
    }

    onFail(event, result) {
        var me = this;

        var outputElement = me._element.querySelector(me.messageContainerSelector);
        var messageElement = outputElement.querySelector(me.outputElementSelector);

        messageElement.innerHTML = me.sendingProblemMessage;
        outputElement.classList.add(me.activeClass);

    }

}