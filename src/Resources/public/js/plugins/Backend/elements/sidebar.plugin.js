class SidebarPlugin extends PluginBase {

    toggleIndicatorClass = "small";
    toggleCookieName = "sidebarState";


    init() {
        var me  = this;

        this.setGivenState();
        this.registerEvents();
    }

    setGivenState() {
        var me = this;


        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${this.toggleCookieName}=`);
        if (parts.length === 2) {
            let cookieValue = parts.pop().split(';').shift();

            if(cookieValue === this.toggleIndicatorClass) {
                me._element.classList.add(this.toggleIndicatorClass);
            }
        }

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
            document.cookie=`${this.toggleCookieName}=;path=/`;
        }
        else {
            me._element.classList.add(me.toggleIndicatorClass);
            document.cookie=`${this.toggleCookieName}=${this.toggleIndicatorClass};path=/`;
        }
    }



}
Pluginmanager.registerPlugin("webu/backend/sidebar", SidebarPlugin, "[data-sidebar]");