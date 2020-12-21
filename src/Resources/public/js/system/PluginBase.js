class PluginBase extends BaseClass {


    /**
     * @param {HTMLElement} element
     * @param {string} namespace
     * @param {string} pluginName
     * @private
     */
    constructor(element, namespace) {
        super();

        this._element = element;
        this._namespace = namespace;
        this._pluginName = this.constructor.name;
    }


    init() {
        throw new Error(`The "init" method for the plugin "${this._pluginName}" is not defined.`);
    }



}

