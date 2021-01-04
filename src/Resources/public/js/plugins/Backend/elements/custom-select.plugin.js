/*
<select class="webu-custom-select" name="test" id="example">
    <option value="A">Option A</option>
    <option value="B">Option B</option>
    <option value="C" selected>Option C</option>
</select>
 */

class CustomSelectPlugin extends PluginBase {

    customSelectClass = "webu-custom-select-container";
    customSelectOptionContainerClass = "webu-custom-select-option-container";
    customSelectOptionClass = "webu-custom-select-option";
    customSelectSelectionClass = "webu-custom-select-selection";


    init() {
        var me = this;

        me.createStructure();

        me.addElementEvents();
    }

    addElementEvents() {

        var me = this;
        let options = me.customOptionContainer.childNodes;

        for (let option of options) {
            jQuery(option).on("click", me.optionClickedEvent.bind(this));
        }

    }

    optionClickedEvent(param) {
        var me = this;


        let target = param.target;

        me.hiddenValueElement.value = target.dataset.value;
        jQuery(me.hiddenValueElement).trigger("change");
        me.activeElement.innerText = target.innerText;
        me.activeElement.dataset.currentOption = target.dataset.optionId;

        me.customOptionContainer.style.display = "none";
        window.setTimeout(function (container) {
            container.style.display = "block";
        }, 10, me.customOptionContainer);


    }


    createStructure() {
        var me = this;

        let element = this._element;
        let selectOptions = element.querySelectorAll("option");


        let hiddenValueElement = document.createElement("input");
        hiddenValueElement.setAttribute("name", element.getAttribute("name"));
        hiddenValueElement.classList = element.classList;
        hiddenValueElement.id = element.id;
        hiddenValueElement.type = "hidden";


        let customSelect = document.createElement("div");
        customSelect.classList.add(me.customSelectClass);


        hiddenValueElement.value = element.value;

        let activeElement = document.createElement("div");
        activeElement.classList.add(me.customSelectSelectionClass);


        var customOptionContainer = document.createElement("div");
        customOptionContainer.classList.add(me.customSelectOptionContainerClass);

        var counter = 0;


        for (let option of selectOptions) {

            let newEl = document.createElement("div");
            newEl.classList.add(me.customSelectOptionClass);
            newEl.id = option.id;
            newEl.dataset.value = option.value;
            newEl.innerText = option.innerText.trim();
            newEl.dataset.optionId = counter + "";

            if (counter === 0 || option.selected) {
                activeElement.dataset.currentOption = counter;
                activeElement.innerText = option.innerText.trim();
            }

            counter++;

            customOptionContainer.appendChild(newEl);
        }


        me.hiddenValueElement = hiddenValueElement;
        me.activeElement = activeElement;
        me.customOptionContainer = customOptionContainer;

        customSelect.appendChild(activeElement);
        customSelect.appendChild(customOptionContainer);


        let parent = element.parentNode;
        parent.replaceChild(customSelect, element);
        customSelect.append(hiddenValueElement);
    }

}

Pluginmanager.registerPlugin("webu/backend/customSelect", CustomSelectPlugin, "select.webu-custom-select");