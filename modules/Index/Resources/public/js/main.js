import PluginManager from "../~Base/System/PluginManager";


import ScrollupHeaderPlugin from "./Plugins/scrollup-header.plugin";


PluginManager.register("webu.scrollupHeaderPlugin", ScrollupHeaderPlugin, "#header");
