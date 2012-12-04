define(
    [
        'text!templates/documentItem.html',
        'underscore',
        'backbone'
    ],
    function (template, _, Backbone) {
        return Backbone.View.extend({
            template: _.template(template),

            events: {
                'click .edit': 'onClickEdit',
                'click .delete': 'onClickDelete'
            },

            initialize: function (options) {
                this.documentModel = options.documentModel;
                this.ventilator = options.ventilator;

                $('.main').addClass('loading');
            },

            render: function () {
                this.$el.html(this.template(this.documentModel.toViewJSON()));
            },

            onClickEdit: function (e) {
                e.preventDefault();

                this.ventilator.trigger('document:edit', this.documentModel.get('id'));
            },

            onClickDelete: function (e) {
                e.preventDefault();

                this.documentModel.destroy();

                this.ventilator.trigger('canvas:message:notice', 'Document successfully deleted');
                this.ventilator.trigger('document:all');
            }
        });
    }
);
