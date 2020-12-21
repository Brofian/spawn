class PluginManager extends BaseClass {

    registeredPluginList = [];
    pluginsLinked = false;
    elementLinks = [];


    init() {
        var me = this;


        document.addEventListener(
            "DOMContentLoaded",
            me.linkPlugins.bind(me),
            false
        );

    };

    /**
     * @param {string} namespace
     * @param {class} pluginClass
     * @param {string} binding
     */
    registerPlugin(namespace, pluginClass, binding = "") {
        var me = this;

        namespace = namespace.toString();
        binding = binding.toString();

        me.registeredPluginList[namespace] = [
            pluginClass,
            binding,
            namespace
        ];


        if(me.pluginsLinked) {

            me.removeElementLink(namespace);
            me.linkPlugin(pluginClass, binding, namespace);

        }
    };


    unregisterPlugin(namespace) {
        var me = this;
        me.registeredPluginList[namespace] = undefined;
        me.removeElementLink(namespace);
    };



    linkPlugins() {
        var me = this;

        for(let pluginEntry in me.registeredPluginList) {

            var entry = me.registeredPluginList[pluginEntry];
            if(entry.length !== 3) continue;

            me.linkPlugin(entry[0], entry[1], entry[2]);
        }

        me.pluginsLinked = true;
    };


    linkPlugin(pluginClass, binding, namespace) {
        var me = this;

        var elements = [];

        if(binding === "") {
            elements = document.querySelectorAll("html");
        }
        else {
            elements = document.querySelectorAll(binding);
        }



        for(var element of elements) {

            var cls = new pluginClass(
                element,
                namespace
            );
            cls.init();

            me.elementLinks.push({
                "namespace": namespace,
                "element" : element,
                "class" : cls
            });
        }



    };

    removeElementLink(namespace) {
        var me = this;
        var links = [];

        for(var link of me.elementLinks) {
            if(link[0] !== namespace) {
                links.push(link);
            }
        }

        me.elementLinks = links;
    }

}



Pluginmanager = new PluginManager();
window.Pluginmanager = Pluginmanager;
Pluginmanager.init();

