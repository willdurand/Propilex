define(
    [
        'models/Document',
        'underscore',
        'jquery',
        'backbone',
        'moment'
    ],
    function (DocumentModel, _, $, Backbone, moment) {
        "use strict";

        return Backbone.Collection.extend({
            model: DocumentModel,
            page:  1,
            total: 0,
            limit: 0,

            url: function () {
                return [
                    $('body').data('api-url'),
                    '/documents',
                    '?page=', this.page
                ].join('');
            },

            parse: function (responseObject) {
                this.page  = responseObject.page;
                this.total = responseObject.pages;
                this.limit = responseObject.limit;

                return responseObject.documents;
            },

            presenter: function () {
                return _.map(this.models, function (model) {
                    return model.presenter();
                });
            },

            comparator: function (documentModel) {
                return - documentModel.getCreatedAt().unix();
            },

            previousPage: function () {
                if (this.page > 1) {
                    this.page = this.page - 1;
                }
            },

            nextPage: function () {
                var max = Math.ceil(this.total / this.limit);

                if (this.page < max) {
                    this.page = this.page + 1;
                }
            }
        });
    }
);
