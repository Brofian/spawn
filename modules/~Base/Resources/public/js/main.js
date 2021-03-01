import PluginManager from "./System/PluginManager";





document.addEventListener('readystatechange', (event) => {
    if (event.target.readyState === 'complete') {
        PluginManager.initializePlugins();
    }
}, false);
