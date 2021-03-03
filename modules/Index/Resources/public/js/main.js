import PluginManager from "PluginManager";

import AjaxFormPlugin from "./Plugins/ajaxform.plugin";
import ScrollupHeaderPlugin from "./Plugins/scrollup-header.plugin";
import ContactFormPlugin from "./Plugins/contactform.plugin";


PluginManager.register("webu.ajaxFormPlugin", AjaxFormPlugin, "[data-ajax-form]");
PluginManager.register("webu.scrollupHeaderPlugin", ScrollupHeaderPlugin, "#header");
PluginManager.register("webu.contactFormPlugin", ContactFormPlugin, "#contact-form");
