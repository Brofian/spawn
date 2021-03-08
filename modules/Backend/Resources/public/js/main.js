import PluginManager from "PluginManager";

import SidebarPlugin from "./Plugins/sidebar.plugin";




PluginManager.register("webu/backend/sidebar", SidebarPlugin, "[data-sidebar]");