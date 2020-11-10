const { resolve, join } = require("path");
const webpack = require("webpack");
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const config = {
    entry: "./resources/js/app.js",
    output: {
        path: resolve(__dirname, "public"),
        filename: "[name].js",
        publicPath: "/",
    },
    module: {
        rules: [
            {
                test: /\.css$/i,
                use: [MiniCssExtractPlugin.loader, 'css-loader'],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
        })
    ],
    resolve: {
        extensions: [".js", ".jsx", ".ts", ".tsx", ".css", ".sass", ".scss"],
    },
};

module.exports = config;
