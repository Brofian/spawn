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


        let deleteMessageDismissButton = me.$_element.find(".dismiss-button");
        deleteMessageDismissButton.on("click", me.onDeleteMessageDismissButton.bind(this));

        let deleteMessageContinueButton = me.$_element.find(".continue-button");
        deleteMessageContinueButton.on("click", me.onDeleteMessageContinueButton.bind(this));

    }

    registerListingEvents() {
        var me = this;

        let deleteButtons = me.$_element.find("[data-link-ajax]");
        deleteButtons.on("click", me.onDeleteButtonClicked.bind(this));

    }


    onDeleteMessageDismissButton(event) {
        var me = this;

        var selectedElements = me._element.querySelectorAll(".deletion-selected");
        for (let selectedElement of selectedElements) {
            selectedElement.classList.remove("deletion-selected")
        }

        document.querySelector("#webu-variables-listing-delete-message").classList.remove("active");
    }


    onDeleteMessageContinueButton(event) {
        var me = this;

        var selectedElements = me._element.querySelectorAll(".deletion-selected");
        var deletedIds = [];
        for (let selectedElement of selectedElements) {
            deletedIds.push(selectedElement.dataset.id);
            selectedElement.parentNode.removeChild(selectedElement);
        }


        jQuery.ajax({
            url: "/backendapi/variablesapi/remove",
            data: {
                idlist: deletedIds
            },
            error: function (result) {
                console.error("An error occured! Variables couldnt be deleted!");
            }
        });


        document.querySelector("#webu-variables-listing-delete-message").classList.remove("active");
    }

    onDeleteButtonClicked(event) {
        var me = this;

        let target = event.currentTarget;
        let row = target.parentNode.parentNode.parentNode;
        if (row.classList.contains("deletion-selected")) {
            row.classList.remove("deletion-selected");
        } else {
            row.classList.add("deletion-selected");
        }

        let deleteMessage = document.querySelector("#webu-variables-listing-delete-message");
        deleteMessage.classList.add("active");
    }


    onChangeItemsPerPage() {
        var me = this;

        let selectedValue = me._element.querySelector("#webu-items-per-page").value;

        let cookie = readCookie(me.cookieName);
        if (cookie !== selectedValue) {
            createCookie(me.cookieName, selectedValue, 0, "/backend/variables");
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
            success: function (result) {
                Eventmanager.triggerEvent("webu/backend/variables/listResult", result);
            }
        });

    }


    onAjaxResult(result) {
        var me = this;

        let tbody = me._element.querySelector(me.contentTargetClass);

        if (tbody) {
            tbody.innerHTML = result;
        }

        me.registerListingEvents();
    }

}

Pluginmanager.registerPlugin("webu/backend/variables/list", VariablesListPlugin, "[data-backend-variables-list]");