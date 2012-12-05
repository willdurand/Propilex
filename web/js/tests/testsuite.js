(function () {
    QUnit.config.autostart = false;

    require.config({
        baseUrl: "../propilex",
        paths: {
            'i18n': '/components/requirejs-i18n/i18n',
            'jquery': '/components/jquery/jquery',
            'underscore': '/components/lodash/lodash.min'
        }
    });

    var testModules = [
        'tTest.js'
    ];

    require(testModules, QUnit.start);
}());
