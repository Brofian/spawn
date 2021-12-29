var webpack = require('webpack');
const path = require('path');
const babelrc = require('./.babelrc');

//
// To start the compiling of the javascript, run the following command in THIS directory:
// npx webpack --config webpack.config.js
//


module.exports = {
    mode: "production", // "production" | "development" | "none"

    //output folder and file
    output: {
        filename: 'index.js'
    },
    //folder to search for imported files
    resolve: {
        modules: [
            'node_modules',
        ],
        alias: {
            PluginManager:  path.resolve(__dirname, './plugin-system/PluginManager'),
            Plugin:         path.resolve(__dirname, './plugin-system/Plugin'),
            CookieManager:  path.resolve(__dirname, './plugin-system/CookieManager'),
            DeviceManager:  path.resolve(__dirname, './plugin-system/DeviceManager'),
            EventManager:   path.resolve(__dirname, './plugin-system/EventManager')
        }
    },

    module: {
       rules: [
           {
               test: /\.m?js$/,
               exclude: /node_modules/,
               use: [
                  {
                      loader: 'babel-loader',
                      options: {
                           ...babelrc,
                           cacheDirectory: true,
                      }
                  }
               ]
           }
       ]
    },

    plugins: [
        new webpack.ProvidePlugin({
             $: require.resolve('jquery'),
             jQuery: require.resolve('jquery')
        }),
     ],
};


