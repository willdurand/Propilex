define(
    [
        'text!templates/documents.html'
    ],
    function (template) {
        return Backbone.View.extend({
            template: _.template(template),

            events: {
                'click .document-item': 'onClick'
            },

            initialize: function (options) {
                this.documentCollection = options.documentCollection;
                this.vent = options.vent;
            },

            render: function () {
                this.$el.html(this.template({
                    collection: this.documentCollection.toJSON()
                }));
            },

            onClick: function (e) {
                e.preventDefault();

                var id = $(e.currentTarget).data("id");

                this.vent.trigger('document:detail', id);
            }
        });
    }
);
