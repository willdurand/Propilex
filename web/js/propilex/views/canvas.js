define(
    [
        'text!templates/canvas.html',
        'underscore',
        'backbone'
    ],
    function (template, _, Backbone) {
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
