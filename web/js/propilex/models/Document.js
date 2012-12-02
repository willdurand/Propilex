define(
    function (require) {
        return Backbone.Model.extend({

            defaults: {
                title: '',
                body: ''
            },

            url : function() {
                return $('body').data('api-url') + '/documents/' + this.id;
            },

            parse: function (responseObject) {
                if (responseObject.document) {
                    return responseObject.document;
                }

                return responseObject;
            }
        });
    }
);
