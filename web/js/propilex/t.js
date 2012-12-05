define(
    [
        'i18n!nls/strings'
    ],
    function (strings) {
        var t = function (key, params) {
            "use strict";

            var string = this.t.data[key] || key;

            if (typeof params !== "undefined") {
                this.each(params, function (key, value) {
                    string = string.replace('%' + key + '%', value);
                });
            }

            return string;
        };

        t.data = strings;

        return t;
    }
);
