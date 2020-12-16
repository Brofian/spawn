import Plugin from 'src/plugin-system/plugin.class';

export default class AddToCartPlugin extends Plugin {

    static options = {
        test: 'test',
    };

    init() {

    }

}

console.log("Test Plugin");