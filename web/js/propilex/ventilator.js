define(
    [
        'underscore',
        'backbone'
    ],
    function (_, Backbone) {
        "use strict";

        return _.extend({}, Backbone.Events);
    }
);
