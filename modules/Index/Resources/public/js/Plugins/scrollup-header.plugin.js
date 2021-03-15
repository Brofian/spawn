import Plugin from "Plugin";

export default class ScrollupHeaderPlugin extends Plugin {

    vanishDistance = 150;

    static lastScrollY = 0;


    init() {
        var me = this;
        me.addEventListener();
        me.onScroll();
    }

    addEventListener() {
        var me = this;

        document.addEventListener("scroll", me.onScroll.bind(me));
    }


    onScroll() {
        var me = this;

        var scrollHeight = window.scrollY;

        var scrollDiff = me.lastScrollY - scrollHeight;

        //set header scrollup-class
        if(scrollHeight === 0) me._element.classList.add("is-top");
        else me._element.classList.remove("is-top");


        if (scrollHeight > me.vanishDistance) {

            if(scrollDiff > 0) {
                me._element.classList.add("is-scrollup");
                me._element.classList.remove("is-scrolldown");
            }
            else {
                me._element.classList.remove("is-scrollup");
                me._element.classList.add("is-scrolldown");
            }

        }
        else {
            me._element.classList.remove("is-scrollup");
            me._element.classList.remove("is-scrolldown");
        }

        me.lastScrollY = scrollHeight;


    }


}