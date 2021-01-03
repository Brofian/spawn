/*
<select class="webu-custom-select" name="test" id="example">
    <option value="A">Option A</option>
    <option value="B">Option B</option>
    <option value="C" selected>Option C</option>
</select>
 */

class CustomHref extends PluginBase {

    init() {
        var me  = this;

        me.createStructure();

    }


    createStructure() {
        var me = this;

        let element = this._element;


        let anchorElement = document.createElement("a");
        anchorElement.href = element.dataset.href;
        if(element.dataset.hrefTarget) {
            anchorElement.target = element.dataset.hrefTarget;
        }


        //append current element to anchor
        let elementCopy = element.cloneNode(true);
        anchorElement.appendChild(elementCopy);

        let parent = element.parentNode;
        parent.replaceChild(anchorElement, element);
    }

}
Pluginmanager.registerPlugin("webu/backend/customHref", CustomHref, "[data-href]");