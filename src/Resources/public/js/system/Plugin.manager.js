import PluginBaseClass from 'src/plugin-system/plugin.class';

export default class PluginManager {

    constructor() {
        window.PluginManager = this;
    }

    /**
     * Registers a plugin to the plugin manager.
     *
     * @param {string} pluginName
     * @param {Plugin} pluginClass
     * @param {string|NodeList|HTMLElement} selector
     * @param {Object} options
     *
     * @returns {*}
     */
    static register(pluginName, pluginClass, selector = document, options = {}) {
        return PluginManagerInstance.register(pluginName, pluginClass, selector, options);
    }

    /**
     * Removes a plugin from the plugin manager.
     *
     * @param {string} pluginName
     * @param {string} selector
     *
     * @returns {*}
     */
    static deregister(pluginName, selector) {
        return PluginManagerInstance.deregister(pluginName, selector);
    }

    /**
     * Extends an already existing plugin with a new class or function.
     * If both names are equal, the plugin will be overridden.
     *
     * @param {string} fromName
     * @param {string} newName
     * @param {Plugin} pluginClass
     * @param {string|NodeList|HTMLElement} selector
     * @param {Object} options
     *
     * @returns {boolean}
     */
    static extend(fromName, newName, pluginClass, selector, options = {}) {
        return PluginManagerInstance.extend(fromName, newName, pluginClass, selector, options);
    }

    static override(overrideName, pluginClass, selector, options = {}) {
        return PluginManagerInstance.extend(overrideName, overrideName, pluginClass, selector, options);
    }



    /**
     * Initializes all plugins which are currently registered.
     */
    static initializePlugins() {
        PluginManagerInstance.initializePlugins();
    }


}

window.PluginManager = PluginManager;
window.PluginBaseClass = PluginBaseClass;