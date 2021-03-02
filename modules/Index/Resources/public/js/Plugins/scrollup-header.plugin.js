import Plugin from "Plugin";

export default class ScrollupHeaderPlugin extends Plugin {

    lastScrollY = 0;

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

        if (scrollDiff > 0) me._element.classList.add("is-scrollup");
        else me._element.classList.remove("is-scrollup");

        me.lastScrollY = scrollHeight;


    }


}