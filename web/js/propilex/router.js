define(
    function (require) {
        var vent = _.extend({}, Backbone.Events);

        return new (Backbone.Router.extend({

            routes: {
                '': 'all',
                'document/:id': 'get',
                'document/new': 'new'
            },

            all: function () {
                var DocumentCollection = require('collections/Document'),
                    DocumentListView = require('views/Document/List'),
                    documentCollection,
                    documentsView,
                    that = this;

                documentCollection = new DocumentCollection();
                documentsView      = new DocumentListView({
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

                vent.on('document:new', function () {
                    that.navigate('document/new', { trigger: true });
                });
            },

            get: function (id) {
                var DocumentModel = require('models/Document'),
                    DocumentItemView = require('views/Document/Item'),
                    documentModel,
                    documentView;

                documentModel = new DocumentModel({ id: id });
                documentView  = new DocumentItemView({
                    documentModel: documentModel
                });

                documentModel.fetch({
                    success: function () {
                        documentView.render();
                    }
                });

                $('.main').html(documentView.el);
            },

            new: function () {
            }
        }))();
    }
);
