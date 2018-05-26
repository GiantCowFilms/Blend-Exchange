const path = require('path');
require('dotenv').config({path: path.resolve(__dirname,'../.env')});
console.log(`Using ${process.env.ENVIRONMENT} configuration.`);

const VueLoaderPlugin = require('vue-loader/lib/plugin')

/**
 * Base Configuration
 */
var baseConfiguration = {
    context: path.resolve(__dirname, '../client'), 
    entry: {
        scripts: path.resolve(__dirname, '../client/bootstrap.js'), 
    },
    output: {
        path: path.resolve(__dirname, '../public/js/'),
        filename: 'blend-exchange.js',
        publicPath: '/js'
    },
    module: {
        rules: [
          {
            test: /\.vue$/,
            loader: 'vue-loader'
          },
          {
            test: /\.css$/,
            use: [
              'vue-style-loader',
              'css-loader'
            ]
          }
        ]
    },
    resolve: {
        extensions: ['*', '.js', '.vue', '.json'],
        alias: {
            '@': path.resolve(__dirname, '../client/'),
            '@P': path.resolve(__dirname, '../client/Components/Pages'),
            '@C': path.resolve(__dirname, '../client/Components')
        }
    },
    plugins: [
      new VueLoaderPlugin()
    ]
};

/**
 * Dev Configuration
 */

 var devConfiguration = {
    mode: 'development',
    watch: true
 }

 /**
 * Production Configuration
 */

var prodConfiguration = {
    mode: 'production'
}

/**
 * Combine Configurations
 */

var configuration;
if(process.env.ENVIRONMENT === 'development') {
    configuration = devConfiguration;
} else {
    configuration = prodConfiguration;
}
configuration = Object.assign(configuration,baseConfiguration);

module.exports = configuration;