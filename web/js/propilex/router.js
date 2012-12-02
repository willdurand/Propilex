define(
    function (require) {
        var vent = _.extend({}, Backbone.Events);

        return new (Backbone.Router.extend({

            routes: {
                '': 'all',
                'document/:id': 'get'
            },

            all: function () {
                var DocumentCollection = require('collections/Document'),
                    DocumentsView = require('views/Documents'),
                    documentCollection,
                    documentsView,
                    that = this;

                documentCollection = new DocumentCollection();
                documentsView      = new DocumentsView({
                    documentCollection: documentCollection,
                    vent: vent
                });

                documentCollection.fetch({
                    success: function () {
                        documentsView.render();
                    }
                });

                $('.main').html(documentsView.el);

                vent.on('document:detail', function (documentId) {
                    that.navigate('document/' + documentId, { trigger: true });
                });
            },

            get: function (id) {
                var DocumentModel = require('models/Document'),
                    DocumentView = require('views/Document'),
                    documentModel,
                    documentView;

                documentModel = new DocumentModel({ id: id });
                documentView  = new DocumentView({
                    documentModel: documentModel
                });

                documentModel.fetch({
                    success: function () {
                        documentView.render();
                    }
                });

                $('.main').html(documentView.el);
            }
        }))();
    }
);
