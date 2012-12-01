define(
    function (require) {
        return new (Backbone.Router.extend({
            routes: {
                '': 'allDocuments',
                'document/:id': 'document'
            },

            allDocuments: function () {
            },

            document: function (id) {
            }
        });
    }
);
