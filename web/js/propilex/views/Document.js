define(
    [
        'text!templates/document.html'
    ],
    function (template) {
        return Backbone.View.extend({
            template: _.template(template),

            initialize: function (options) {
                this.documentCollection = options.documentCollection;
            },

            render: function () {
                this.$el.html(this.template({
                    collection: this.documentCollection
                }));
            }
        });
    }
);
