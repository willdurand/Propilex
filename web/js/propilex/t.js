define(
    [
        'i18n!nls/strings'
    ],
    function (strings) {
        return function (key, params) {
            "use strict";

            var data = strings || {},
                string = data[key] || key;

            if (typeof params !== "undefined") {
                this.each(params, function (key, value) {
                    string = string.replace('%' + key + '%', value);
                });
            }

            return string;
        };
    }
);
