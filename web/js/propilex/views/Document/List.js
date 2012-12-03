define(
    [
        'text!templates/documentList.html',
        'underscore',
        'backbone'
    ],
    function (template, _, Backbone) {
        return Backbone.View.extend({
            template: _.template(template),

            events: {
                'click .document-item': 'onClickItem',
                'click #document-new': 'onClickNew'
            },

            initialize: function (options) {
                this.documentCollection = options.documentCollection;
                this.vent = options.vent;

                $('.main').addClass('loading');
            },

            render: function () {
                this.$el.html(this.template({
                    collection: this.documentCollection.toViewJSON()
                }));
            },

            onClickItem: function (e) {
                e.preventDefault();

                this.vent.trigger('document:detail', $(e.currentTarget).data('id'));
            },

            onClickNew: function (e) {
                e.preventDefault();

                this.vent.trigger('document:new');
            }
        });
    }
);
