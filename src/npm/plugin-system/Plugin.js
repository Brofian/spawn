export default class Plugin {


    /** @var {HTMLElement} _element*/
    _element = null;
    _$element = null;
    _pluginName = "";
    _selector = "";

    _initialized = false;

    /**
     * @param {HTMLElement} element
     * @param  $element
     * @param {string} pluginName
     * @param {string} selector
     * @private
     */
    constructor(element, $element, pluginName, selector) {
        this._element = element;
        this._$element = $element;
        this._pluginName = pluginName;
        this._selector = selector;

        if(!this._initialized) {
            this.init();
            this._initialized = true;
        }
    }




    init() {
        throw new Error(`The "init" method for the plugin "${this._pluginName}" is not defined.`);
    }


    sendBaseAjaxRequest(url, isPost=false, postData=[], context = null, success=null, error=null) {
        var me = this;

        var method = "get";
        if(isPost) method = "post";

        if(context === null) context = me;

        if(success === null) success = me.onBaseAjaxRequestSuccess.bind(me);
        if(error === null) error = me.onBaseAjaxRequestError.bind(me);

        return $.ajax({
            url: url,
            type: method,
            data: postData,
            context: context,
            success: success,
            error: error,
        });
    }

    onBaseAjaxRequestSuccess() {
        var me = this;
        me.onBaseAjaxRequestResult();
    }
    onBaseAjaxRequestError() {
        var me = this;
        me.onBaseAjaxRequestResult();
    }
    onBaseAjaxRequestResult() {}

}