require({
    deps: ['bootstrap', 'less'],
    paths: {
        'text': '/components/requirejs-text/text',
        'backbone': '/components/backbone/backbone-min',
        'backbone-forms-core': '/components/backbone-forms/distribution/backbone-forms',
        'backbone-forms': '/components/backbone-forms/distribution/templates/bootstrap',
        'jquery': '/components/jquery/jquery',
        'underscore': '/components/underscore/underscore',
        'garlicjs': '/components/garlicjs/garlic',
        'bootstrap': '/components/bootstrap.css/js/bootstrap.min',
        'moment': '/components/moment/moment',
        'less': '/components/less.js/dist/less-1.3.1.min'
    },

    shim: {
        'jquery': {
            'exports': 'jQuery'
        },
        'bootstrap': {
            'deps': ['jquery'],
            'exports': '$'
        },
        'underscore': {
            'exports': '_'
        },
        'backbone': {
            'deps': ['underscore', 'jquery'],
            'exports': 'Backbone'
        },
        'backbone-forms-core': {
            'deps': ['jquery', 'backbone'],
            'exports': 'Backbone'
        },
        'backbone-forms': {
            'deps': ['backbone-forms-core'],
            'exports': 'Backbone'
        },
        'less': {
            'exports': 'less'
        },
        'garlic': {
            'deps': ['jquery'],
            'exports': '$'
        },
        'moment': {
            'deps': ['jquery'],
            'exports': 'moment'
        }
    }
});

require(
    [
        'router',
        'views/canvas'
    ],
    function (router, canvasView) {
        canvasView.render();
        $('body').prepend(canvasView.el);

        Backbone.history.start();
    }
);
