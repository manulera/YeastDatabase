var Encore = require('@symfony/webpack-encore');
const path = require('path');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/') 
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')
    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    .addEntry('collection_type_add', './assets/js/collection_type_add.js')
    .addEntry('allele/allele_viewer', './assets/js/allele/allele_viewer.js')
    .addEntry('strain/source/mating', './assets/js/strain/source/mating.js')
    .addEntry('strain/source/custom_allele', './assets/js/strain/source/custom_allele.js')
    .addEntry('strain/network', './assets/js/strain/network.js')
    .addEntry('custom-file-label', './assets/js/custom-file-label.js')
    .addEntry('form_type/locus_picker', './assets/js/form_type/locus_picker.js')
    .addEntry('form_type/strain_picker', './assets/js/form_type/strain_picker.js')
    .addEntry('external_imports/chosen', './assets/js/external_imports/chosen.js')
    .addEntry('dummy', './assets/js/dummy/dummy.js')
    .addEntry('strain/source/marker_switch', './assets/js/strain/source/marker_switch.js')
    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')
    //TODO: Is this ok?
    .addAliases({'jquery' : require.resolve('jquery'),'knockout' : require.resolve('knockout')})
    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    // .autoProvidejQuery()
    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
    })

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')

;

// module.exports = Encore.getWebpackConfig();

const config = Encore.getWebpackConfig();

config.resolve.alias = {
  handlebars: 'handlebars/dist/handlebars.min.js',
};

module.exports = config;