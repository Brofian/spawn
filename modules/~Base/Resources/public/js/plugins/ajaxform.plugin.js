/*
 * With this plugin, you can mark any form as an AjaxForm by adding the attribute "data-ajax-form"
 * Once a form has this attribute, it will no longer redirect on submit. Instead it will disable all inputs and buttons
 * and send an ajax request.
 * The result of the request can be used by another plugin, by listening to the following jquery events in the form element:
 * - ajaxform:fail   -> called on request failure (404, 500, etc)
 * - ajaxform:success -> called on request success (200)
 * - ajaxform:result -> called on any request result (failure as well as success)
 *
 */



import Plugin from "Plugin";

export default class AjaxFormPlugin extends Plugin {

    init() {
        var me = this;

        me.target = "";
        me.method = "get";
        me.isLoading = false;

        if(me._element.tagName !== "FORM") return;

        me.applyAttributes();
        me.addEventListener();
    }

    applyAttributes() {
        var me = this;

        me.target = me._element.action;
        me.method = me._element.method;
    }

    addEventListener() {
        var me = this;

        me._element.addEventListener('submit', me.onsubmit.bind(me), false);
    }


    onsubmit(event) {
        var me = this;

        //stop event
        event.preventDefault();


        if (me.isLoading) {
            return;
        }

        me.closeForm();

        var data = me.readFormValues();

        //create request

        var request = $.ajax({
            url: me.target,
            type: me.method,
            data: data,
            context: me,
            success: me.onAjaxSuccess.bind(me),
            error: me.onAjaxFail.bind(me),
        });

     }


    closeForm() {
        var me = this;

        $(me._element).find("button,input").prop("disabled", true);
    }

    openForm() {
        var me = this;

        $(me._element).find("button,input").prop("disabled", false);
    }

    readFormValues() {
        var me = this;

        var elements = me._element.elements;
        var valuesObj ={};

        for(var i = 0 ; i < elements.length ; i++){
            var item = elements.item(i);
            valuesObj[item.name] = item.value;
        }

        return valuesObj;
    }




    onAjaxSuccess(result) {
        var me = this;

        me._$element.trigger("ajaxform:success", [result]);
        me.onAjaxResult();
    }

    onAjaxFail(result) {
        var me = this;

        me._$element.trigger("ajaxform:fail", [result]);
        me.onAjaxResult();
    }

    onAjaxResult() {
        var me = this;

        me._$element.trigger("ajaxform:result");
        me.openForm();
    }





}