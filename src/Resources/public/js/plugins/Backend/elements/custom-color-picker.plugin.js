class CustomColorPickerPlugin extends PluginBase {

    previewItemSelector = '.color-picker-preview';

    onChangeHueEvent = 'webu/backendvariables/changeHue';
    onChangeSaturationEvent = 'webu/backendvariables/changeSaturation';
    onChangeLightnessEvent = 'webu/backendvariables/changeLightness';

    default = "#000000";

    rgba = [
        0,
        0,
        0,
        1
    ];

    hsl = [
        0,
        0,
        0,
        1
    ];

    sliders = [
        null,
        null,
        null
    ];

    valueTarget = null;



    init() {
        var me = this;

        me.applyDataAttributes();
        me.findElements();

        me.registerEvents();

        me.setPreviewColor(me.default, true);
        me.rgba = me.hexToRgba(me.default);
    }

    applyDataAttributes() {
        var me = this;

        if (me._element.dataset.default) {
            me.default = me._element.dataset.default;
            if(me.default.charAt(0) !== "#") {
                me.default = "#ff0000";
            }
        }
    }


    findElements() {
        var me = this;

        me.sliders[0] = me._element.querySelector(".hue");
        me.sliders[1] = me._element.querySelector(".saturation");
        me.sliders[2] = me._element.querySelector(".lightness");

        me.valueTarget = me._element.querySelector(".color-picker-value-holder");

    }


    registerEvents() {
        var me = this;

        Eventmanager.subscribeEvent(me.onChangeHueEvent, "webu/customs/colorpicker", me.onHueChange.bind(me));
        Eventmanager.subscribeEvent(me.onChangeSaturationEvent, "webu/customs/colorpicker", me.onSaturationChange.bind(me));
        Eventmanager.subscribeEvent(me.onChangeLightnessEvent, "webu/customs/colorpicker", me.onLightnessChange.bind(me));

    }


    onHueChange(hue) {
        var me = this;

        me.hsl[0] = hue;

        me.updatePreviewColorOnSliderChange();
    }

    onSaturationChange(saturation) {
        var me = this;

        me.hsl[1] = saturation;

        me.updatePreviewColorOnSliderChange();
    }

    onLightnessChange(lightness) {
        var me = this;

        me.hsl[2] = lightness;

        me.updatePreviewColorOnSliderChange();
    }


    updatePreviewColorOnSliderChange() {
        var me = this;

        let hue = me.hsl[0];
        let saturation = me.hsl[1];
        let lightness = me.hsl[2];

        let rgb = me.hslToRgb(hue, saturation, lightness);
        let hex = me.rgbaToHex(rgb["r"], rgb["g"], rgb["b"], 255);

        me.setPreviewColor(hex)

    }


    setPreviewColor(hexColor, setSliders = false) {
        var me = this;

        me._element.querySelector(me.previewItemSelector).style.background = hexColor;

        me.valueTarget.value = hexColor;


        var rgb = this.hexToRgba(hexColor);

        if(setSliders) {
            var hsl = this.rgbToHsl(rgb[0], rgb[1], rgb[2]);


            for(let i = 0; i < 3; i++) {
                console.log(hsl[i]);
                me.sliders[i].dataset.value=hsl[i];
                $(me.sliders[i]).trigger("value_set");
            }
        }
    }


    hslToRgb(h, s, l) {


        s /= 100;
        l /= 100;

        let c = (1 - Math.abs(2 * l - 1)) * s,
            x = c * (1 - Math.abs((h / 60) % 2 - 1)),
            m = l - c / 2,
            r = 0,
            g = 0,
            b = 0;


        if (0 <= h && h < 60) {
            r = c;
            g = x;
            b = 0;
        } else if (60 <= h && h < 120) {
            r = x;
            g = c;
            b = 0;
        } else if (120 <= h && h < 180) {
            r = 0;
            g = c;
            b = x;
        } else if (180 <= h && h < 240) {
            r = 0;
            g = x;
            b = c;
        } else if (240 <= h && h < 300) {
            r = x;
            g = 0;
            b = c;
        } else if (300 <= h && h < 360) {
            r = c;
            g = 0;
            b = x;
        }
        r = Math.round((r + m) * 255);
        g = Math.round((g + m) * 255);
        b = Math.round((b + m) * 255);


        let result = [];
        result["r"] = r;
        result[0] = r;
        result["g"] = g;
        result[1] = g;
        result["b"] = b;
        result[2] = b;

        return result;

    }

    rgbToHsl(r, g, b) {

        r /= 255;
        g /= 255;
        b /= 255;

        let cmin = Math.min(r, g, b);
        let cmax = Math.max(r, g, b);
        let delta = cmax - cmin;
        let h = 0;
        let s = 0;
        let l = 0;

        //Hue

        if (delta === 0) {
            h = 0;
        } else if (cmin === r) {
            h = ((g - b) / delta) % 6;
        } else if (cmax === g) {
            h = (b - r) / delta + 2;
        } else {
            h = (r - g) / delta + 4;
        }

        while (h < 0) {
            h += 360;
        }


        //Lightness
        l = (cmax + cmin) / 2;


        //Saturation

        if (delta === 0) {
            s = 0;
        } else {
            s = 1 - Math.abs(2 * l - 1);
        }


        l = Math.round(l * 100);
        s = Math.round(s * 100);
        h = h / (Math.PI / 180);

        let result = [];
        result["h"] = h;
        result[0] = h;
        result["s"] = s;
        result[1] = s;
        result["l"] = l;
        result[2] = l;

        return result;
    }


    hexToRgba(hex) {
        hex = hex.replace('#', '');

        //hex = parseInt(hex);

        let rgba = [
            0,
            0,
            0,
            1
        ];

        let index = 0;
        for (let i = 0; i < hex.length; i += 2) {
            if (i + 1 >= hex.length) {
                return;
            }

            let str = hex[i] + hex[i + 1];
            rgba[index] = parseInt(str, 16);

            if (index === 3) {
                rgba[index] = 255 / rgba[index];
            }


            index++;
        }


        return rgba;
    }

    rgbaToHex(r, g, b, a = 255) {

        Math.max(r, 0);
        Math.max(g, 0);
        Math.max(b, 0);
        Math.max(a, 0);

        Math.min(r, 255);
        Math.min(g, 255);
        Math.min(b, 255);
        Math.min(a, 255);

        let rHex = r.toString(16);
        let gHex = g.toString(16);
        let bHex = b.toString(16);
        let aHex = a.toString(16);

        if (rHex.length < 2) {
            rHex = "0" + rHex;
        }
        if (gHex.length < 2) {
            gHex = "0" + gHex;
        }
        if (bHex.length < 2) {
            bHex = "0" + bHex;
        }
        if (aHex.length < 2) {
            aHex = "0" + aHex;
        }


        return '#' + rHex + gHex + bHex + aHex;
    }


}

Pluginmanager.registerPlugin("webu/backend/colorPicker", CustomColorPickerPlugin, "[data-color-picker]");