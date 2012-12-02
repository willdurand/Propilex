define(
    function (require) {
        return Backbone.Model.extend({

            url : function() {
                return $('body').data('api-url') + this.id;
            }
        });
    }
);
