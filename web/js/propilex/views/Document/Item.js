define(
    [
        'text!templates/documentItem.html',
        'underscore',
        'jquery',
        'backbone',
        't'
    ],
    function (template, _, $, Backbone, t) {
        return Backbone.View.extend({
            template: _.template(template),

            events: {
                'click .edit': 'onClickEdit',
                'click .delete': 'onClickDelete'
            },

            initialize: function (options) {
                this.documentModel = options.documentModel;
                this.documentCollection = options.documentCollection;
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

                var that = this;
                this.documentModel.destroy().done(function () {
                    that.documentCollection.remove(this.documentModel);

                    that.ventilator.trigger(
                        'canvas:message:notice',
                        t('message.delete', { title: that.documentModel.get('title') })
                    );
                    that.ventilator.trigger('document:all');
                });
            }
        });
    }
);
