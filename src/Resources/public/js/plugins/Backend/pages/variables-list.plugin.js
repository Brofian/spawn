class VariablesListPlugin extends PluginBase {

    contentTargetClass = ".webu-variables-listing-tbody";
    itemsPerPage = 10;
    currentPage = 1;
    cookieName = "variables-list-length";

    init() {
        var me = this;

        me.registerEvents();
        me.itemsPerPage = me._element.querySelector("#webu-items-per-page").value;
        me.loadTableContent();

        Eventmanager.subscribeEvent("webu/backend/variables/listResult", "webu/backend/variables/list", me.onAjaxResult.bind(me));

    }

    registerEvents() {
        var me = this;

        let inputItemsPerPage = me.$_element.find("#webu-items-per-page");
        inputItemsPerPage.on("change", me.onChangeItemsPerPage.bind(this));

    }

    onChangeItemsPerPage() {
        var me = this;

        let selectedValue = me._element.querySelector("#webu-items-per-page").value;

        let cookie = readCookie(me.cookieName);
        if(cookie !== selectedValue) {
            createCookie(me.cookieName,selectedValue,0,"/backend/variables");
            this.itemsPerPage = selectedValue;
        }

        me.loadTableContent();
    }



    loadTableContent() {
        var me = this;


        jQuery.ajax({
            url: "/backendapi/variablesapi/list",
            data: {
                page: me.currentPage,
                itemsPerPage: me.itemsPerPage
            },
            success: function( result ) {
                Eventmanager.triggerEvent("webu/backend/variables/listResult", result);
            }
        });

    }


    onAjaxResult(result) {
        var me = this;

        let tbody = me._element.querySelector(me.contentTargetClass);

        if(tbody) {
            tbody.innerHTML = result;
        }

    }

}
Pluginmanager.registerPlugin("webu/backend/variables/list", VariablesListPlugin, "[data-backend-variables-list]");