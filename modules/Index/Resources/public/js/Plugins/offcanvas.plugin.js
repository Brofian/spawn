import Plugin from "Plugin";

export default class OffcanvasPlugin extends Plugin {


    init() {
        var me = this;

        me.openButtonSelector = "[data-offcanvas-trigger]";
        me.openButtonOffcanvasSelector = "offcanvasId";

        me.closeButtonSelector = "[data-can-close-offcanvas]";
        me.closeButtonOffcanvasSelector = "offcanvasId";

        me.offcanvasSelector = ".offcanvas";
        me.isOpenClass = "is-open";

        me.registerEventListeners();
    }


    registerEventListeners() {
        var me = this;


        //register an open-event on all elements with the openButtonSelector
        for(var openButton of document.querySelectorAll(me.openButtonSelector)) {
            if(openButton.dataset[me.openButtonOffcanvasSelector]) {
                openButton.addEventListener("click", me.openOffcanvas.bind(me,openButton.dataset[me.openButtonOffcanvasSelector]));
                openButton.addEventListener("touch", me.openOffcanvas.bind(me,openButton.dataset[me.openButtonOffcanvasSelector]));
            }
        }


        //register a close-event on all elements with the closeButtonSelector
        for(var closeButton of document.querySelectorAll(me.closeButtonSelector)) {
            if(closeButton.dataset[me.closeButtonOffcanvasSelector]) {
                closeButton.addEventListener("click", me.closeOffcanvas.bind(me,closeButton.dataset[me.closeButtonOffcanvasSelector]));
                closeButton.addEventListener("touch", me.closeOffcanvas.bind(me,closeButton.dataset[me.closeButtonOffcanvasSelector]));
            }
            else {
                closeButton.addEventListener("click", me.closeAllOffcanvas.bind(me));
                closeButton.addEventListener("touch", me.closeAllOffcanvas.bind(me));
            }
        }

    }


    closeAllOffcanvas() {
        var me = this;

        for(var offCanvas of document.querySelectorAll(me.offcanvasSelector)) {
            if(offCanvas) {
                offCanvas.classList.remove(me.isOpenClass);
            }
        }
    }

    closeOffcanvas(id) {
        var me = this;

        var offCanvas = document.querySelector("#" + id);

        if(offCanvas) {
            offCanvas.classList.remove(me.isOpenClass);
        }
    }


    openOffcanvas(id) {
        var me = this;

        var offCanvas = document.querySelector("#" + id);

        if(offCanvas) {
            offCanvas.classList.add(me.isOpenClass);
        }
    }

}