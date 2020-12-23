class BackendLoginPlugin extends PluginBase {


    init() {
        var me = this;

        Eventmanager.subscribeEvent("webu/backend/loginSubmitResult", "webu/backend/login", me.onAjaxResult.bind(me));
        me.addElementEvents();
    }

    addElementEvents() {
        var me = this;

        me.$_element.on("submit", me.submit.bind(this));
    }


    submit(event) {
        var me = this;

        event.preventDefault();

        let username = me._element.querySelector("#login-username").value;
        let password = me._element.querySelector("#login-password").value;


        jQuery.ajax({
            url: "/backend/loginapi/",
            data: {
                username: username,
                password: password
            },
            success: function( result ) {
                Eventmanager.triggerEvent("webu/backend/loginSubmitResult", result);
            }
        });

    }


    onAjaxResult(result) {
        var me = this;

        result = JSON.parse(result)["success"];


        if(result === 1) {
            window.location.replace("/backend");
        }
        else {
            me._element.classList.add("error");
        }

        //let res = JSON.parse(result);
        //console.log("Execute on Ajax Request: " + result);
    }

}
Pluginmanager.registerPlugin("webu/backend/login", BackendLoginPlugin, "[data-backend-login-form]");