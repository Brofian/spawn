class TestPlugin extends PluginBase {


    init() {
        Eventmanager.subscribeEvent("webu/system/scroll", "/webu/testplugin", this.onscroll.bind(this));
    }

    onscroll() {
        //console.log("hello world");
    }


}
Pluginmanager.registerPlugin("webu/testplugin", TestPlugin);