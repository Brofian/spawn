import Plugin from "Plugin";
import CookieManager from "CookieManager";

export default class SidebarPlugin extends Plugin {


    init() {
        var me = this;

        me.toggleIndicatorClass = "small";
        me.toggleCookieName = "sidebarState";

        this.setGivenState();
        this.registerEvents();
    }

    setGivenState() {
        var me = this;

        let sidebarState = CookieManager.getCookie(me.toggleCookieName);

        if(sidebarState === me.toggleIndicatorClass) {
            me._element.classList.add(me.toggleIndicatorClass);
        }

    }

    registerEvents() {
        var me = this;

        var sidebarToggleButton = me._element.querySelector("#sidebar-toggle-button");
        sidebarToggleButton.addEventListener("click", me.toggleSidebar.bind(this));
    }

    toggleSidebar() {
        var me = this;

        if (me._element.classList.contains(me.toggleIndicatorClass)) {
            me._element.classList.remove(me.toggleIndicatorClass);
            CookieManager.createCookie(me.toggleCookieName,"wide");
        } else {
            me._element.classList.add(me.toggleIndicatorClass);
            CookieManager.createCookie(me.toggleCookieName,me.toggleIndicatorClass);
        }
    }


}

