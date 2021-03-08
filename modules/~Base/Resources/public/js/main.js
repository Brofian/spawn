import PluginManager from "PluginManager";

import AjaxFormPlugin from "./Plugins/ajaxform.plugin";
import DomLoadedPlugin from "./plugins/domloaded.plugin";


PluginManager.register("webu.ajaxFormPlugin", AjaxFormPlugin, "[data-ajax-form]");
PluginManager.register('webu.system.domloadedplugin', DomLoadedPlugin);