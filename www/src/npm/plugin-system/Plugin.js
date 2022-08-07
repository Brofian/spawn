import EventManager from "EventManager";

export default class Plugin {

    /** @var {HTMLElement} _element*/
    _element = null;
    _$element = null;
    _pluginName = "";
    _selector = "";
    static options = {};

    _initialized = false;

    /**
     * @param {HTMLElement} element
     * @param  $element
     * @param {string} pluginName
     * @param {string} selector
     * @param {object} configuration
     * @private
     */
    constructor(element, $element, pluginName, selector, configuration = {}) {
        this._element = element;
        this._$element = $element;
        this._pluginName = pluginName;
        this._selector = selector;
        this.options = configuration;

        if(!this._initialized) {
            this._setup();
            this.init();
            this._initialized = true;
        }
    }

    _setup() {
        // load options from dataset
        if(typeof this.options === 'object') {
            for(let option in this.options) {
                if(this.options.hasOwnProperty(option)) {
                    let prop = this.options[option];
                    this.options[option] = this._element.dataset[option] ?? prop;
                }
            }
        }
    }

    onPluginElementUpdate() {
        // empty placeholder
    }

    init() {
        throw new Error(`The "init" method for the plugin "${this._pluginName}" is not defined.`);
    }

}