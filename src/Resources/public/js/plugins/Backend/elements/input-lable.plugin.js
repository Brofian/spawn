/*
    <input type="text"
           name="username"
           id="login-username"
           data-label="Benutzername"
           //data-label-position: top
           class="webu-input-text"
           required >
 */

class InputLabelPlugin extends PluginBase {


    init() {
        var me  = this;

        me.createStructure()
    }

    createStructure() {
        var me = this;

        var element = this._element;

        let labelPosition = "inner";
        if(element.dataset.labelPosition) {
            labelPosition = element.dataset.labelPosition;
        }


        if(labelPosition === "inner") {
            element.style.paddingTop = "1em";
            element.style.paddingBottom = "1em";
        }
        else if(labelPosition === "top") {
            element.style.marginTop = "1.5em";
        }


        var parent = element.parentNode;

        var wrapper = document.createElement('div');
        wrapper.classList.add("webu-input-wrapper");
        //move layout classes to wrapper
        for(let elClass of element.classList) {
            if(elClass.startsWith("col-")) {
                element.classList.remove(elClass);
                wrapper.classList.add(elClass);
            }
        }


        var label = document.createElement('span');
        label.classList.add("webu-input-label");
        if(labelPosition === "top") {
            label.classList.add("top");
        }
        label.innerText = element.dataset.label;


        parent.replaceChild(wrapper, element);
        wrapper.appendChild(element);
        wrapper.appendChild(label);
    }

}
Pluginmanager.registerPlugin("webu/backend/inputLabel", InputLabelPlugin, "input[data-label]");