class VariablesNewPlugin extends PluginBase {

    ajaxEvent = "webu/backendvariables/formSubmitResult";


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

        var name = me._element.querySelector("#variable-name-input").value;
        var namespace = me._element.querySelector("#variable-namespace-input").value;
        var type = me._element.querySelector("#variable-type-input").value;
        var value = me._element.querySelector("#variable-value-input").value;
        var id = me._element.querySelector("#variable-id-input").value;


        //send ajax request for change / addition
        //on ajax return show error or redirect to page


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

        console.log(result);


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

        }


    }


}

Pluginmanager.registerPlugin("webu/backend/variables/new", VariablesNewPlugin, "[data-backend-variables-form]");