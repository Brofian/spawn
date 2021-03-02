import PluginManager from "PluginManager";

import ScrollupHeaderPlugin from "./Plugins/scrollup-header.plugin";
import AjaxFormPlugin from "./Plugins/ajaxform.plugin";


PluginManager.register("webu.scrollupHeaderPlugin", ScrollupHeaderPlugin, "#header");
PluginManager.register("webu.ajaxFormPlugin", AjaxFormPlugin, "[data-ajax-form]");
