import PluginManagerSubject from "./PluginManagerSubject";

export const PluginManagerInstance = new PluginManagerSubject();


export default class PluginManager {

    constructor() {
    }


    static register(plugin, pluginClass, binding) {
        return PluginManagerInstance.register(plugin, pluginClass, binding);
    }


    static unregister(pluginName, binding = "") {
        return PluginManagerInstance.unregister(pluginName, binding);
    }


    static getPluginList() {
        return PluginManagerInstance.getPluginList();
    }

    static initializePlugins(scope) {
        return PluginManagerInstance.initializePlugins(scope);
    }

    static purgeRegisteredPlugins() { return PluginManagerInstance.purgeRegisteredPlugins() }
}
window.PluginManager = new PluginManager();

document.addEventListener('readystatechange', (event) => {
    if (event.target.readyState === 'complete') {
        PluginManager.initializePlugins(document);
    }
}, false);