class VariablesNewPlugin extends PluginBase {

    ajaxEvent = "webu/backendvariables/formSubmitResult";

    isFormDisabled = false;

    init() {
        var me = this;

        me.registerEvents();
    }

    registerEvents() {
        var me = this;

        Eventmanager.subscribeEvent(me.ajaxEvent, "webu/backend/variables", me.onAjaxResult.bind(me));

        me.$_element.on("submit", this.onFormSubmit.bind(this));
    }

    onFormSubmit(event) {
        var me = this;

        event.preventDefault();

        if(me.isFormDisabled) {
            return;
        }

        me.isFormDisabled = true;

        var name = me._element.querySelector("#variable-name-input").value;
        var namespace = me._element.querySelector("#variable-namespace-input").value;
        var type = me._element.querySelector("#variable-type-input").value;
        var value_text = me._element.querySelector("#variable-value-input-text").value;
        var value_number = me._element.querySelector("#variable-value-input-number").value;
        var value_color = me._element.querySelector("#variable-value-input-color").value;
        var id = me._element.querySelector("#variable-id-input").value;

        if(value_number === "") value_number = 0;

        //send ajax request for change / addition
        //on ajax return show error or redirect to page


        var value = "";
        switch(type) {
            case "text":
                value = value_text;
                break;
            case "number":
                value = value_number;
                break;
            case "color":
                value = value_color;
                break;
        }


        jQuery.ajax({
            url: "/backendapi/variablesapi/edit",
            data: {
                id: id,
                name: name,
                namespace: namespace,
                type: type,
                value: value
            },
            success: function (result) {
                Eventmanager.triggerEvent(me.ajaxEvent, result);
            }
        });

    }


    onAjaxResult(result) {

        var me = this;

        try {
            result = JSON.parse(result);
        }
        catch(e) {
            console.error(e);
            return;
        }

        if(result["success"]) {
            window.location = "/backend/variables/";
        }
        else {
            me.isFormDisabled = false;
        }


    }


}

Pluginmanager.registerPlugin("webu/backend/variables/new", VariablesNewPlugin, "[data-backend-variables-form]");