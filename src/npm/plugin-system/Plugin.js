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

}