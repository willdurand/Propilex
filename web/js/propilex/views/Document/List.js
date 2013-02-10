define(
    [
        'text!templates/documentList.html',
        'underscore',
        'jquery',
        'backbone'
    ],
    function (template, _, $, Backbone) {
        "use strict";

        return Backbone.View.extend({
            template: _.template(template),

            events: {
                'click .document-item': 'onClickItem',
                'click #document-new': 'onClickNew',
                'click .prev': 'onClickPrev',
                'click .next': 'onClickNext'
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

                this.ventilator.trigger('document:get', $(e.currentTarget).data('id'));
            },

            onClickNew: function (e) {
                e.preventDefault();

                this.ventilator.trigger('document:create');
            },

            onClickPrev: function (e) {
                e.preventDefault();

                var view = this;

                this.documentCollection.previousPage();
                this.documentCollection.fetch().done(function () {
                    view.render();
                });
            },

            onClickNext: function (e) {
                e.preventDefault();

                var view = this;

                this.documentCollection.nextPage();
                this.documentCollection.fetch().done(function () {
                    view.render();
                });
            }
        });
    }
);
