class PluginBase {

    /** @var {HTMLElement} _element*/
    _element = null;
    $_element = null;
    _namespace = "";
    _pluginName = "";

    /**
     * @param {HTMLElement} element
     * @param  $element
     * @param {string} namespace
     * @private
     */
    constructor(element, $element, namespace) {

        this._element = element;
        this.$_element = $element;
        this._namespace = namespace;
        this._pluginName = this.constructor.name;
    }


    init() {
        throw new Error(`The "init" method for the plugin "${this._pluginName}" is not defined.`);
    }



}

