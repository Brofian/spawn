import PluginManagerSubject from "./PluginManagerSubject";

export const PluginManagerInstance = new PluginManagerSubject();


export default class PluginManager {

    constructor() {
        window.PluginManager = this;
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

    static initializePlugins() {
        return PluginManagerInstance.initializePlugins();
    }

}
new PluginManager();