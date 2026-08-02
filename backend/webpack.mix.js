const mix = require('laravel-mix');
const WebpackRTLPlugin = require('webpack-rtl-plugin')

// Webpack RTL plugin
mix.webpackConfig({
  plugins: /* mix.inProduction() ? */ [new WebpackRTLPlugin()] /* : [] */,
})

// CSS
mix.sass('resources/scss/app.scss', 'public/css')
mix.sass('resources/scss/vendor.scss', 'public/css')

// JavaScript
mix.js('resources/js/app.js', 'public/js')
mix.js('resources/js/vendor.js', 'public/js')

// Disable popup notifications
mix.disableNotifications()
