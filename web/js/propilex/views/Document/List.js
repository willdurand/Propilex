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
                this.ventilator = options.ventilator;

                $('.main').addClass('loading');
            },

            render: function () {
                this.$el.html(this.template({
                    collection: this.documentCollection.presenter()
                }));
            },

            onClickItem: function (e) {
                e.preventDefault();

                this.ventilator.trigger('document:detail', $(e.currentTarget).data('id'));
            },

            onClickNew: function (e) {
                e.preventDefault();

                this.ventilator.trigger('document:new');
            }
        });
    }
);
