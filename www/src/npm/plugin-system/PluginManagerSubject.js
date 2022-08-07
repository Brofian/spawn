export default class PluginManagerSubject {

    registeredPluginList = [];
    pluginsInitialized = false;
    initializedPluginList = [];
    processedElements = [];



    getPluginList() {
        return this.registeredPluginList;
    }

    /**
     * @param {string} pluginName
     * @param {class} pluginClass
     * @param {string} binding
     */
    register(pluginName, pluginClass, binding = "") {
        var me = this;

        if(binding==="") binding="html";


        var plugin = {
            class: pluginClass,
            binding: binding.toString(),
            name: pluginName.toString()
        };

        me.registeredPluginList.push(plugin);

        if(me.pluginsInitialized) {
            me.initializePlugin(plugin);
        }
    };


    unregister(pluginName, binding = "") {
        var me = this;

        var registeredPlugins = [];

        for(let plugin of me.registeredPluginList) {
            if(plugin.name !== pluginName.toString() || (binding !== "" && plugin.binding) !== binding.toString()) {
                registeredPlugins.push(plugin);
            }
            else {
                me.removeInitializedPlugin(plugin);
            }
        }

        me.registeredPluginList = registeredPlugins;
    };



    initializePlugins(scope) {
        var me = this;

        for(let plugin of me.registeredPluginList) {
            me.initializePlugin(plugin.class, plugin.binding, plugin.name, scope);
        }

        me.pluginsInitialized = true;
    }


    initializePlugin(pluginClass, pluginBinding, pluginName, scope) {
        var me = this;

        if (typeof pluginClass !== 'function') {
            throw new Error('The passed plugin is not a function or a class.');
        }

        // cleanup stale references
        if(this.processedElements[pluginName]) {
            for(let ref of this.processedElements[pluginName]) {
                if(!document.contains(ref)) {
                    let index;
                    while((index = this.processedElements[pluginName].indexOf(ref)) > -1) {
                        this.processedElements[pluginName].splice(index, 1);
                    }
                    this.processedElements[pluginName].remove
                }
            }
        }


        var boundElements = scope.querySelectorAll(pluginBinding);

        for(let boundElement of boundElements ) {

            if(this.processedElements[pluginName] && this.processedElements[pluginName].includes(boundElement)) {
                // element is already processed -> search it and run the update method
                for(let el of this.initializedPluginList) {
                    if(el.plugin === pluginClass && el.element === boundElement) {
                        el.instance.onPluginElementUpdate();
                        break;
                    }
                }

                continue;
            }

            var element = {
                plugin: pluginClass,
                element: boundElement,
                instance: new pluginClass(
                    boundElement,
                    jQuery(boundElement),
                    pluginName,
                    pluginBinding,
                    pluginClass.options
                )
            };

            me.initializedPluginList.push(element);

            if(!this.processedElements[pluginName]) {
                this.processedElements[pluginName] = [];
            }
            this.processedElements[pluginName].push(boundElement);
        }
    }

    purgeRegisteredPlugins() {
        var me = this;

        let remIndices = [];
        for(let i of me.initializedPluginList) {
            let exists = document.contains(i.instance._element);
            if(!exists) {
                remIndices.push(i);
            }
        }

        //sort by highest
        remIndices.sort(function(a, b) {
            return b-a;
        });


        for(let i of remIndices) {
            me.initializedPluginList.splice(i,1);
        }
    }


    removeInitializedPlugin(plugin) {
        var me = this;

        me.initializedPluginList = me.initializedPluginList.filter(function(obj) {
            return (
                obj.plugin.name !== plugin.name &&
                (obj.plugin.binding === "" || obj.plugin.binding === plugin.binding )
            );
        });
    }


}