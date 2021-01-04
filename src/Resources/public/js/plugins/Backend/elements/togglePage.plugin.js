class TogglePagePlugin extends PluginBase {

    toggle = null;
    toggleCase = "true";
    activeClass = "active";
    inactiveClass = "inactive";


    init() {
        var me  = this;

        me.toggle = jQuery(document).find("#" + me._element.dataset.togglePageToggleId);
        me.toggleCase = me._element.dataset.togglePageCase;


        if(!me.toggle) {
            return;
        }

        me.registerEvents();
        me.onToggleValueChanged({
            currentTarget: me.toggle[0]
        });

    }

    registerEvents() {
        var me = this;

        me.toggle.on("change", me.onToggleValueChanged.bind(this));
    }

    onToggleValueChanged(event) {
        var me = this;

        var newValue = event.currentTarget.value;

        if(newValue === me.toggleCase) {
            me._element.classList.add("active");
            me._element.classList.remove("inactive");
            me._element.innerHTML = me._element.innerHTML.replace("id-inactive=", "id=");
            me._element.innerHTML = me._element.innerHTML.replace("name-inactive=", "name=");
        }
        else {
            me._element.classList.add("inactive");
            me._element.classList.remove("active");
            me._element.innerHTML = me._element.innerHTML.replace("id=", "id-inactive=");
            me._element.innerHTML = me._element.innerHTML.replace("name=", "name-inactive=");
        }
    }


}
Pluginmanager.registerPlugin("webu/backend/togglePage",  TogglePagePlugin, "[data-toggle-page]");