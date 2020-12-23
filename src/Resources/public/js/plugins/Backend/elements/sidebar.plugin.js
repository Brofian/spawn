class SidebarPlugin extends PluginBase {

    toggleIndicatorClass = "small";


    init() {
        var me  = this;

        this.registerEvents();
    }

    registerEvents() {
        var me = this;

        var sidebarToggleButton = me.$_element.find("#sidebar-toggle-button");
        sidebarToggleButton.on("click", me.toggleSidebar.bind(this));
    }

    toggleSidebar() {
        var me = this;

        if(me._element.classList.contains(me.toggleIndicatorClass)) {
            me._element.classList.remove(me.toggleIndicatorClass);
        }
        else {
            me._element.classList.add(me.toggleIndicatorClass);
        }
    }



}
Pluginmanager.registerPlugin("webu/backend/sidebar", SidebarPlugin, "[data-sidebar]");