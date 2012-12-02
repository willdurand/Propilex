define(
    function (require) {
        return new (Backbone.Router.extend({
            routes: {
                '': 'allDocuments',
                'document/:id': 'document'
            },

            allDocuments: function () {
                var DocumentCollection = require('collections/Document'),
                    DocumentView = require('views/Document'),
                    documentCollection,
                    documentView;

                documentCollection = new DocumentCollection();

                documentView = new DocumentView({
                    documentCollection: documentCollection
                });

                documentCollection.fetch();
                documentView.render();

                $('.main').html(documentView.el);
            },

            document: function (id) {
                var DocumentModel = require('models/Document'),
                    documentModel;

                documentModel = new DocumentModel({ id: id });
            }
        }))();
    }
);
