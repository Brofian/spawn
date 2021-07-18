export default class DeviceManagerSubject {

    static touchDevice = false;

    constructor() {

        this.touchDevice = (
            ('ontouchstart' in window) ||
            (navigator.maxTouchPoints > 0) ||
            (navigator.msMaxTouchPoints > 0)
        );

    }

    /**
     * @returns {boolean}
     */
    static isTouchDevice() {
        return this.touchDevice;
    }


}