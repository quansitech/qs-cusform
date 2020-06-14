const merge = require('webpack-merge');
const baseConfig = require('./webpack.base.conf.js');
const path = require('path');

module.exports = merge(baseConfig, {
    mode: 'production',
    output: {
        filename: 'cusform-bundle.js',
        path: path.join(__dirname, 'dist'),
        publicPath: '/Public/cusform/'
    }
});