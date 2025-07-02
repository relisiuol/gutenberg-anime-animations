const defaultConfig   = require('@wordpress/scripts/config/webpack.config');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const path = require('path');

module.exports = {
    ...defaultConfig,
    entry: {
        editor: './src/editor.js',
        frontend: './src/frontend.js'
    },

    externals: {
        ...defaultConfig.externals,
        animejs: 'anime',
    },

    plugins: [
        ...defaultConfig.plugins,

        new CopyWebpackPlugin({
            patterns: [
                {
                from: path.resolve(
                    __dirname,
                    'node_modules',
                    'animejs',
                    'lib',
                    'anime.umd.min.js'
                ),
                to: path.resolve(__dirname, 'build', 'anime.min.js'),
                },
            ],
        }),
    ],
};
