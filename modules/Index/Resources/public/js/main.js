import PluginManager from "PluginManager";

import AjaxFormPlugin from "./Plugins/ajaxform.plugin";
import ScrollupHeaderPlugin from "./Plugins/scrollup-header.plugin";
import ContactFormPlugin from "./Plugins/contactform.plugin";
import OffcanvasPlugin from "./Plugins/offcanvas.plugin";
import CookieConsentPlugin from "./Plugins/cookieconsent.plugin";


PluginManager.register("webu.ajaxFormPlugin", AjaxFormPlugin, "[data-ajax-form]");
PluginManager.register("webu.scrollupHeaderPlugin", ScrollupHeaderPlugin, "#header");
PluginManager.register("webu.contactFormPlugin", ContactFormPlugin, "#contact-form");
PluginManager.register("webu.offcanvasPlugin", OffcanvasPlugin, "#offcanvas-container");
PluginManager.register("webu.cookieconsentPlugin", CookieConsentPlugin, "#cookie-consent");
