define(
    [
        'i18n!nls/strings',
        'underscore'
    ],
    function (strings, _) {
        return function (key, params) {
            "use strict";

            var data = strings || {},
                string = data[key] || key;

            if (typeof params !== "undefined") {
                _.each(params, function (key, value) {
                    string = string.replace('%' + key + '%', value);
                });
            }

            return string;
        };
    }
);
