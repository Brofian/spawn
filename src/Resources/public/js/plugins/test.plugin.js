class TestPlugin extends PluginBase {


    init() {
        console.log("Test Plugin Init");
    }


}
Pluginmanager.registerPlugin("webu/testplugin", TestPlugin, "[data-backend]");