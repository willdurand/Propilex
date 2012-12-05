define(
    [
        'text!templates/documentItem.html',
        'underscore',
        'jquery',
        'backbone'
    ],
    function (template, _, $, Backbone) {
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
                this.$el.html(this.template(this.documentModel.presenter()));
            },

            onClickEdit: function (e) {
                e.preventDefault();

                this.ventilator.trigger('document:edit', this.documentModel.get('id'));
            },

            onClickDelete: function (e) {
                e.preventDefault();

                this.documentModel.destroy();

                this.ventilator.trigger(
                    'canvas:message:notice',
                    $.t('message.delete', { title: this.documentModel.get('title') })
                );
                this.ventilator.trigger('document:all');
            }
        });
    }
);
