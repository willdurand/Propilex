(function () {
    QUnit.config.autostart = false;

    require.config({
        baseUrl: '../propilex',
        paths: {
            'i18n': '../../components/requirejs-i18n/i18n',
            'jquery': '../../components/jquery/jquery',
            'backbone': '../../components/backbone/backbone-min',
            'underscore': '../../components/lodash/lodash.min',
            'moment': '../../components/moment/moment'
        },
        config: {
            i18n: {
                locale: 'tests'
            }
        },
        shim: {
            'backbone': {
                'deps': [ 'underscore', 'jquery' ],
                'exports': 'Backbone'
            }
        }
    });

    var testModules = [
        'tTest.js',
        'models/DocumentTest.js'
    ];

    require(testModules, QUnit.start);
}());
