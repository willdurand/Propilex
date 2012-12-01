define(function (require) {
    return Backbone.Model.extend({
        url : function() {
            return return $('body').data('api-url') + this.id;
        }
    });
});
