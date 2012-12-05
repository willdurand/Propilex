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

            initialize: function (models, options) {
                this.url = $('body').data('api-url') + '/documents/';
            },

            parse: function (responseObject) {
                return responseObject.documents;
            },

            presenter: function () {
                return _.map(this.models, function (model) {
                    return model.presenter();
                });
            },

            comparator: function (documentModel) {
                var createdAt = moment(
                        documentModel.get('created_at').date,
                        documentModel.dateFormat
                    );

                return - createdAt.unix();
            }
        });
    }
);
