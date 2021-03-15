import Plugin from "Plugin";

export default class LoginformPlugin extends Plugin {


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

        try {
            var parsed_result = JSON.parse(result);
        }
        catch(e) {
            console.log(parsed_result);

            me.onFail();
            return;
        }
        console.log(parsed_result);


        if(parsed_result["success"] && parsed_result["success"]==="true") {
            //some success event
            console.log("success");

            window.location.href = parsed_result["target"];
        }
        else {
            me.onFail();
        }
    }

    onFail() {
        var me = this;

        var inputs = this._element.querySelectorAll("input");

        for(var input of inputs) {
            input.classList.add("wiggle");

            window.setTimeout((function(input) {
                input.classList.remove("wiggle");
            }).bind(null,input), 500);
        }

    }

}