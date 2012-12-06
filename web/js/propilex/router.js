define(
    [
        'ventilator',
        'jquery',
        'backbone',
        't',
        'models/Document',
        'collections/Document',
        'views/Document/Item',
        'views/Document/Form',
        'views/Document/List'
    ],
    function (ventilator, $, Backbone, t, DocumentModel, DocumentCollection, DocumentItemView, DocumentFormView, DocumentListView) {
        "use strict";

        return new (Backbone.Router.extend({

            documentCollection: new DocumentCollection(),

            routes: {
                '': 'all',
                'document/new': 'create',
                'document/:id': 'get',
                'document/:id/edit': 'edit'
            },

            initialize: function () {
                ventilator.on('document:all', function () {
                    this.navigate('', { trigger: true });
                }, this);

                ventilator.on('document:create', function () {
                    this.navigate('document/new', { trigger: true });
                }, this);

                ventilator.on('document:get', function (documentId) {
                    this.navigate('document/' + documentId, { trigger: true });
                }, this);

                ventilator.on('document:edit', function (documentId) {
                    this.navigate('document/' + documentId + '/edit', { trigger: true });
                }, this);
            },

            all: function () {
                var documentsView;

                documentsView = new DocumentListView({
                    documentCollection: this.documentCollection,
                    ventilator: ventilator
                });

                documentsView.render();
                $('.main').html(documentsView.el);

                this.documentCollection.fetch()
                    .done(function () {
                        documentsView.render();
                        $('.main').removeClass('loading');
                    })
                    .fail(function () {
                        ventilator.trigger('canvas:message:error', t('message.error.fetch'));
                        $('.main').removeClass('loading');
                    });
            },

            get: function (id) {
                var documentModel,
                    documentView,
                    that = this;

                documentModel = new DocumentModel({ id: id });
                documentView  = new DocumentItemView({
                    documentModel: documentModel,
                    documentCollection: this.documentCollection,
                    ventilator: ventilator
                });

                documentView.render();
                $('.main').html(documentView.el);

                documentModel.fetch()
                    .done(function () {
                        documentView.render();
                        $('.main').removeClass('loading');
                    })
                    .fail(function () {
                        that.all();
                    });
            },

            create: function () {
                var documentModel,
                    documentView;

                documentModel = new DocumentModel();
                documentView  = new DocumentFormView({
                    documentModel: documentModel,
                    ventilator: ventilator
                });

                documentView.render();
                $('.main').html(documentView.el);
            },

            edit: function (id) {
                var documentModel,
                    documentView;

                documentModel = new DocumentModel({ id: id });
                documentView  = new DocumentFormView({
                    documentModel: documentModel,
                    ventilator: ventilator
                });

                documentView.render();
                $('.main').html(documentView.el);

                documentModel.fetch().done(function () {
                    documentView.render();
                    $('.main').removeClass('loading');
                });
            }
        }))();
    }
);
