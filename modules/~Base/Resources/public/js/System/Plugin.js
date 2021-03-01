export default class Plugin {

    /** @var {HTMLElement} _element*/
    _element = null;
    $_element = null;
    _pluginName = "";

    /**
     * @param {HTMLElement} element
     * @param  $element
     * @param {string} pluginName
     * @private
     */
    constructor(element, $element, pluginName) {
        this._element = element;
        this.$_element = $element;
        this._pluginName = pluginName;
    }



    init() {
        throw new Error(`The "init" method for the plugin "${this._pluginName}" is not defined.`);
    }

}