define(
    [
        'models/Document'
    ],
    function (DocumentModel) {
        return Backbone.Collection.extend({
            model: DocumentModel,

            initialize: function (models, options) {
                this.url = $('body').data('api-url') + '/documents';
            },

            parse: function (responseObject) {
                return responseObject.documents;
            },

            toViewJSON: function () {
                return _.map(this.models, function (model) {
                    return model.toViewJSON();
                });
            }
        });
    }
);
