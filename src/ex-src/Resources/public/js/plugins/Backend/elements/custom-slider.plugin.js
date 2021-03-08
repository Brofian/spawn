class CustomSliderPlugin extends PluginBase {

    cursorSelector = ".custom-slider-cursor";

    min = 0;
    max = 100;
    value = 0;
    target = "";
    ajaxEvent = "webu/custom/changeCustomSlider";

    cursor = null;


    targetElement = null;
    cursorElement = null;

    mouseIsDown = false;




    init() {
        var me  = this;

        me.applyDataAttributes();

        me.targetElement = document.querySelector(me.target);
        if(!me.targetElement) return;

        me.cursorElement = me._element.querySelector(me.cursorSelector);




        let percentage = me.value / ((me.max - me.min) / 100.0);
        percentage = Math.min(percentage, 100);
        percentage = Math.max(percentage, 0);
        me.setCursorElement(percentage);




        me.registerEventListeners();

        me.onChange();
    }


    registerEventListeners() {
        var me = this;

        me._element.addEventListener('click', me.onUserInput.bind(me), true);
        me._element.addEventListener('mousedown', me.onUserMouseDown.bind(me), true);
        me._element.addEventListener('mousemove', me.onUserMouseMove.bind(me), true);
        document.addEventListener('mouseup', me.onUserMouseUp.bind(me), true);

        me.$_element.on("value_set", me.onValueSet.bind(me));
    }

    onValueSet() {
        var me = this;

        console.log(me._element.dataset.value);
        //me.value = me._element.dataset.value;
    }


    onUserInput(event) {
        var me = this;

        let elementBounds = event.target.getBoundingClientRect();

        let mouseX = event.clientX;
        let elementX = elementBounds.left;
        let relativeX = mouseX - elementX;

        let percentage = relativeX / (elementBounds.width / 100.0);

        percentage = Math.min(percentage, 100);
        percentage = Math.max(percentage, 0);

        me.setCursorElement(percentage);


        me.value = Math.round(((me.max - me.min) * (percentage / 100.0)) - (-me.min));

        me.onChange();
    }


    onUserMouseMove(event) {
        var me = this;
        if(!me.mouseIsDown) return;

        this.onUserInput(event);
    }

    onUserMouseDown(event) {
        var me = this;
        me.mouseIsDown = true;
    }

    onUserMouseUp(event) {
        var me = this;
        me.mouseIsDown = false;
    }



    onChange() {
        var me = this;

        me.targetElement.value = me.value;

        Eventmanager.triggerEvent(me.ajaxEvent, this.value);
    }


    setCursorElement(percentage) {
        var me = this;

        if(me.cursorElement) {
            me.cursorElement.style.left = percentage + "%";
        }
    }

    applyDataAttributes() {
        var me = this;

        let dataset = me._element.dataset;

        if(dataset["min"]) me.min = dataset["min"];
        if(dataset["max"]) me.max = dataset["max"];
        if(dataset["value"]) me.value = dataset["value"];
        if(dataset["target"]) me.target = dataset["target"];
        if(dataset["event"]) me.ajaxEvent = dataset["event"];
    }


}
Pluginmanager.registerPlugin("webu/backend/slider",  CustomSliderPlugin, "[data-custom-slider]");