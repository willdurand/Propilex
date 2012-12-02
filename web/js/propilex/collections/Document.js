define(
    [
        'models/Document'
    ],
    function (DocumentModel) {
        return Backbone.Collection.extend({
            model: DocumentModel,

            initialize: function (models, options) {
                this.url = $('body').data('api-url') + '/documents';
            }
        });
    }
);
