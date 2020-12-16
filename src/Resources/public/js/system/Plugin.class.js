/**
 * Plugin Base class
 */
export default class Plugin {
    /**
     * plugin constructor
     *
     * @param {HTMLElement} el
     * @param {Object} options
     * @param {string} pluginName
     */
    constructor(el, options = {}, pluginName = false) {
        if (!DomAccess.isNode(el)) {
            throw new Error('There is no valid element given.');
        }

        this.el = el;
        this.$emitter = new NativeEventEmitter(this.el);
        this._pluginName = this._getPluginName(pluginName);
        this.options = this._mergeOptions(options);
        this._initialized = false;

        this._registerInstance();
        this._init();
    }

    /**
     * this function gets executed when the plugin is initialized
     */
    init() {
        throw new Error(`The "init" method for the plugin "${this._pluginName}" is not defined.`);
    }


}