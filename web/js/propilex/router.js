define(
    function (require) {
        var vent = _.extend({}, Backbone.Events);

        return new (Backbone.Router.extend({

            routes: {
                '': 'all',
                'document/new': 'create',
                'document/:id': 'get',
                'document/:id/edit': 'edit'
            },

            initialize: function () {
                var that = this;

                vent.on('document:detail', function (documentId) {
                    that.navigate('document/' + documentId, { trigger: true });
                });

                vent.on('document:new', function () {
                    that.navigate('document/new', { trigger: true });
                });

                vent.on('document:edit', function (documentId) {
                    that.navigate('document/' + documentId + '/edit', { trigger: true });
                });

                vent.on('document:all', function () {
                    that.navigate('', { trigger: true });
                });
            },

            all: function () {
                var DocumentCollection = require('collections/Document'),
                    DocumentListView = require('views/Document/List'),
                    documentCollection,
                    $documentsView;

                documentCollection = new DocumentCollection();
                documentsView      = new DocumentListView({
                    documentCollection: documentCollection,
                    vent: vent
                });

                documentCollection.fetch().done(function () {
                    documentsView.render();
                    $('.main').removeClass('loading');
                });

                documentsView.render();
                $('.main').html(documentsView.el);
            },

            get: function (id) {
                var DocumentModel = require('models/Document'),
                    DocumentItemView = require('views/Document/Item'),
                    documentModel,
                    documentView,
                    that = this;

                documentModel = new DocumentModel({ id: id });
                documentView  = new DocumentItemView({
                    documentModel: documentModel,
                    vent: vent
                });

                documentModel.fetch()
                    .done(function () {
                        documentView.render();
                        $('.main').removeClass('loading');
                    })
                    .fail(function () {
                        that.all();
                    });

                documentView.render();
                $('.main').html(documentView.el);
            },

            create: function () {
                var DocumentModel = require('models/Document'),
                    DocumentFormView = require('views/Document/Form'),
                    documentModel,
                    documentView;

                documentModel = new DocumentModel();
                documentView  = new DocumentFormView({
                    documentModel: documentModel,
                    vent: vent
                });

                documentView.render();
                $('.main').html(documentView.el);
            },

            edit: function (id) {
                var DocumentModel = require('models/Document'),
                    DocumentFormView = require('views/Document/Form'),
                    documentModel,
                    documentView;

                documentModel = new DocumentModel({ id: id });
                documentView  = new DocumentFormView({
                    documentModel: documentModel,
                    vent: vent
                });

                documentModel.fetch().done(function () {
                    documentView.render();
                    $('.main').removeClass('loading');
                });

                documentView.render();
                $('.main').html(documentView.el);
            }
        }))();
    }
);
