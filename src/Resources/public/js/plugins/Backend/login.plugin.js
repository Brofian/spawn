class BackendLoginPlugin extends PluginBase {


    init() {
        Eventmanager.subscribeEvent("webu/backend/loginSubmitResult", "webu/backend/login", this.onAjaxResult.bind(this));
        this.addElementEvents();
    }

    addElementEvents() {
        this.$_element.on("submit", this.submit.bind(this));
    }


    submit(event) {

        let username = this._element.querySelector("#login-username").value;
        let password = this._element.querySelector("#login-password").value;


        jQuery.ajax({
            url: "backend/loginapi/",
            data: {
                username: username,
                password: password
            },
            success: function( result ) {
                Eventmanager.triggerEvent("webu/backend/loginSubmitResult", result);
            }
        });

        event.preventDefault();
    }


    onAjaxResult(result) {
        if(result) {
            var reloadElement = document.createElement("meta");
            reloadElement.httpEquiv = "refresh";
            reloadElement.content = "0";
            document.querySelector("head").appendChild(reloadElement);
        }
        else {

        }

        //let res = JSON.parse(result);
        console.log("Execute on Ajax Request: " + result);
    }


    /*
        <form data-backend-login-form>
            <input type="text"
                   name="username"
                   data-label="Benutzername"
                   class="webu-input-text">

            <input type="text"
                   name="password"
                   data-label="Passwort"
                   class="webu-input-text">

            <input type="submit" class="webu-input-submit">
        </form>
     */


}
Pluginmanager.registerPlugin("webu/backend/login", BackendLoginPlugin, "[data-backend-login-form]");