/*globals requirejs: true */
require({
    deps: [
        'bootstrap',
        'less'
    ],

    paths: {
        'text': '/components/requirejs-text/text',
        'backbone': '/components/backbone/backbone-min',
        'backbone-forms-core': '/components/backbone-forms/distribution/backbone-forms.min',
        'backbone-forms': '/components/backbone-forms/distribution/templates/bootstrap',
        'jquery': '/components/jquery/jquery.min',
        'underscore': '/components/lodash/lodash.min',
        'garlicjs': '/components/garlicjs/garlic',
        'bootstrap': '/components/bootstrap.css/js/bootstrap.min',
        'moment-core': '/components/moment/moment',
        'moment': '/components/moment/lang/fr',
        'less': '/components/less.js/dist/less-1.3.1.min',
        'i18n': '/components/requirejs-i18n/i18n',
        'key': '/components/keymaster/keymaster'
    },

    shim: {
        'bootstrap': {
            'deps': [ 'jquery' ]
        },
        'backbone': {
            'deps': [ 'underscore', 'jquery' ],
            'exports': 'Backbone'
        },
        'backbone-forms-core': {
            'deps': [ 'jquery', 'backbone' ]
        },
        'backbone-forms': {
            'deps': [ 'backbone-forms-core' ]
        },
        'garlic': {
            'deps': [ 'jquery' ]
        },
        'moment-core': {
            'deps': [ 'jquery' ]
        },
        'moment': {
            'deps': [ 'moment-core' ]
        },
        'key': {
            'exports': 'key'
        }
    },

    config: {
        i18n: {
            locale: localStorage.getItem('locale') || 'en-us'
        }
    }
});

require(
    [
        'router',
        'views/canvas',
        'jquery',
        'backbone',
        't',
        'moment',
        'ventilator'
    ],
    function (router, canvasView, $, Backbone, t, moment, ventilator) {
        "use strict";

        // expose t in templates
        $.t = t;

        // configure ajax error handling
        $.ajaxSetup({
            statusCode: {
                500: function () {
                    ventilator.trigger('canvas:message:error', t('error.internal_server_error'));
                    $('.main').removeClass('loading');
                }
            },
            headers: {
                'Accept': 'application/hal+json',
                'Accept-Language': requirejs.s.contexts._.config.config.i18n.locale.substr(0, 2)
            }
        });

        // set language thanks to the i18n locale value
        moment.lang(requirejs.s.contexts._.config.config.i18n.locale.substr(0, 2));

        canvasView.render();
        $('body').prepend(canvasView.el);

        Backbone.history.start();
    }
);
