define(
    [
        'i18n!nls/strings',
        'underscore'
    ],
    function (strings, _) {
        "use strict";

        return function (key, params) {
            var string = strings[key] || key;

            if (typeof params !== "undefined") {
                _.each(params, function (value, key) {
                    string = string.replace('%' + key + '%', value);
                });
            }

            return string;
        };
    }
);
