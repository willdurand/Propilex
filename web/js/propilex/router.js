define(
    function (require) {
        var vent = _.extend({}, Backbone.Events);

        return new (Backbone.Router.extend({

            routes: {
                '': 'all',
                'document/new': 'create',
                'document/:id': 'get',
            },

            listenToEvents: function () {
                var that = this;

                vent.off('document:detail');
                vent.on('document:detail', function (documentId) {
                    that.navigate('document/' + documentId, { trigger: true });
                });

                vent.off('document:new');
                vent.on('document:new', function () {
                    that.navigate('document/new', { trigger: true });
                });
            },

            all: function () {
                var DocumentCollection = require('collections/Document'),
                    DocumentListView = require('views/Document/List'),
                    documentCollection,
                    $documentsView;

                this.listenToEvents();

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

            create: function () {
                var DocumentModel = require('models/Document'),
                    DocumentFormView = require('views/Document/Form'),
                    documentModel,
                    documentView;

                this.listenToEvents();

                documentModel = new DocumentModel();
                documentView  = new DocumentFormView({
                    documentModel: documentModel,
                    vent: vent
                });

                documentView.render();

                $('.main').html(documentView.el);
            }
        }))();
    }
);
