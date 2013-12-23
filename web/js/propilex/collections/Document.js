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
            pages: 0,
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
                this.pages = responseObject.pages;
                this.limit = responseObject.limit;

                return responseObject._embedded.documents;
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
                if (this.page < this.pages) {
                    this.page = this.page + 1;
                }
            }
        });
    }
);
