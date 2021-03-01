export default class Plugin {

    /** @var {HTMLElement} _element*/
    _element = null;
    _$element = null;
    _pluginName = "";

    _initialized = false;

    /**
     * @param {HTMLElement} element
     * @param  $element
     * @param {string} pluginName
     * @private
     */
    constructor(element, $element, pluginName) {
        this._element = element;
        this._$element = $element;
        this._pluginName = pluginName;

        if(!this._initialized) {
            this.init();
            this._initialized = true;
        }
    }



    init() {
        throw new Error(`The "init" method for the plugin "${this._pluginName}" is not defined.`);
    }

}