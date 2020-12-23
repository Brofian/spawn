class InputLabelPlugin extends PluginBase {


    init() {
        var me  = this;

        me.createStructure()
    }

    createStructure() {
        var me = this;

        var element = this._element;

        element.style.paddingTop = "1em";
        element.style.paddingBottom = "1em";
        var parent = element.parentNode;

        var wrapper = document.createElement('div');
        wrapper.classList.add("webu-input-wrapper");
        var label = document.createElement('span');
        label.classList.add("webu-input-label");
        label.innerText = element.dataset.label;


        parent.replaceChild(wrapper, element);
        wrapper.appendChild(element);
        wrapper.appendChild(label);
    }

}
Pluginmanager.registerPlugin("webu/backend/inputLabel", InputLabelPlugin, "input[data-label]");