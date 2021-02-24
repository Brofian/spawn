const path = require('path');

module.exports = {
    mode: "production", // "production" | "development" | "none"

    //entrypoint for compiling
    entry: '../../../var/cache/private/resources/js/index.js',
    //output folder and file
    output: {
        filename: 'index.js',
        path: path.resolve(__dirname, '../../../var/cache/public/js/'),
    },
    //folder to search for imported files
    resolve: {
        modules: [path.resolve(__dirname, '../../../var/cache/private/resources/js/'), 'node_modules']
    }

};

//
// To start the compiling of the javascript, run the following command in THIS directory:
// npx webpack --config webpack.config.js
//