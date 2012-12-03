define(
    [
        'text!templates/canvas.html'
    ],
    function (template) {
        return new (Backbone.View.extend({
            initialize: function () {
                this.template = _.template(template);
            },
            render: function () {
                this.$el.html(this.template);
            }
        }))();
    }
);
