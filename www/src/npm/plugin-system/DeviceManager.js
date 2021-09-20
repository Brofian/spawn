import DeviceManagerSubject from "./DeviceManagerSubject";

export const DeviceManagerInstance = new DeviceManagerSubject();

export default class DeviceManager {

    /**
     * @returns {boolean}
     */
    static isTouchDevice() {
        return DeviceManagerSubject.isTouchDevice();
    }


}