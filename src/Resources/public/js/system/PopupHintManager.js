/**
 *     PopupHintManager.addPopup("Hello world1", "default", 10);
 */

class PopupHintManager extends BaseClass {

    popups = [];

    isDocumentReady = false;
    storedPopups = [];

    popupContainerId = "webu-popup-container";
    popupElementIdPrefix = "popup-element";
    popupElementClassPrefix = "-type-";
    popupTimerClass = "popup-element-timer";

    increment = 0;

    init() {
        var me = this;

        window.Eventmanager.subscribeEvent("webu/system/DOMContentLoaded","webu/system/popuphintmanager",me.createStoredPopups.bind(me));
    }



    addPopup(message, type, durationSek = 10) {
        var me = this;

        if(!me.isDocumentReady) {
            me.storePopup(message, type, durationSek);
            return;
        }

        var duration = durationSek * 1000;
        if(duration > 100000) duration = 100000;
        if(duration < 3000) duration = 3000;

        let id = me.getNextIncrement();

        //create
        me.createPopupElement(message,type,duration,id);

        //register
        me.popups.push({
            message: message,
            type: type,
            id: id
        });

        //set timeout to remove
        window.setTimeout(me.removePopupElement.bind(me, id), duration);
    }

    storePopup(message, type, duration) {
        var me = this;

        me.storedPopups.push({
            message: message,
            type: type,
            duration: duration
        });
    }

    createStoredPopups() {
        var me = this;
        me.isDocumentReady = true;

        for(let storedPopup of me.storedPopups) {
            me.addPopup(storedPopup.message, storedPopup.type, storedPopup.duration);
        }
        me.storedPopups = [];
    }

    getNextIncrement() {
        return this.increment++;
    }


    removePopupElement(id) {
        var me = this;


        let popup = document.getElementById(me.popupElementIdPrefix + "-" + id);
        popup.remove();


        if(me.popups.length <= 0) {
            let popupContainer = document.getElementById(me.popupContainerId);
            popupContainer.remove();
        }
    }


    createPopupElement(message, type, duration, id) {
        var me = this;


        //create popup container, if not exists
        let popupContainer = document.getElementById(me.popupContainerId);
        if(popupContainer == null) {
            popupContainer = document.createElement('div');
            popupContainer.id = me.popupContainerId;
            document.getElementById("body").appendChild(popupContainer);
        }

        let popup = document.createElement('div');
        popup.id = me.popupElementIdPrefix  + "-" + id;
        popup.classList.add(me.popupElementIdPrefix + me.popupElementClassPrefix + type);
        popup.classList.add(me.popupElementIdPrefix);
        popup.innerText = message;

        //duration
        let popupTimer = document.createElement('span');
        popupTimer.classList.add(me.popupTimerClass);
        popupTimer.style.transition = "width linear " + duration + "ms";
        popup.appendChild(popupTimer);

        popupContainer.appendChild(popup);

        window.setTimeout(me.triggerPopupTimer.bind(me,id), 10);
    }

    triggerPopupTimer(id) {
        var me = this;

        let popup = document.getElementById(me.popupElementIdPrefix  + "-" + id);
        let popupTimer = popup.querySelector("."+me.popupTimerClass);
        popupTimer.style.width = 0;
    }


}


PopupHintManager = new PopupHintManager();
PopupHintManager.init();
window.PopupHintManager = PopupHintManager;

