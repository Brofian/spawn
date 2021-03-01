import PluginManager from "./System/PluginManager";
import Plugin from "./System/Plugin.js";


PluginManager.register("system/plugin", Plugin);




document.addEventListener('readystatechange', (event) => {
    if (event.target.readyState === 'complete') {
        PluginManager.initializePlugins();
    }
}, false);
