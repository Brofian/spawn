import PluginManager from "PluginManager";

import SidebarPlugin from "./Plugins/sidebar.plugin";
import LoginformPlugin from "./Plugins/loginform.plugin";




PluginManager.register("webu/backend/sidebar", SidebarPlugin, "[data-sidebar]");
PluginManager.register("webu/backend/loginform", LoginformPlugin, "[data-backend-login-form]");