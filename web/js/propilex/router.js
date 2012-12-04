define(
    [
        'ventilator',
        'models/Document',
        'collections/Document',
        'views/Document/Item',
        'views/Document/Form',
        'views/Document/List'
    ],
    function (ventilator, DocumentModel, DocumentCollection, DocumentItemView, DocumentFormView, DocumentListView) {
        return new (Backbone.Router.extend({

            routes: {
                '': 'all',
                'document/new': 'create',
                'document/:id': 'get',
                'document/:id/edit': 'edit'
            },

            initialize: function () {
                ventilator.on('document:detail', function (documentId) {
                    this.navigate('document/' + documentId, { trigger: true });
                }, this);

                ventilator.on('document:new', function () {
                    this.navigate('document/new', { trigger: true });
                }, this);

                ventilator.on('document:edit', function (documentId) {
                    this.navigate('document/' + documentId + '/edit', { trigger: true });
                }, this);

                ventilator.on('document:all', function () {
                    this.navigate('', { trigger: true });
                }, this);
            },

            all: function () {
                var documentCollection,
                    documentsView;

                documentCollection = new DocumentCollection();
                documentsView      = new DocumentListView({
                    documentCollection: documentCollection,
                    ventilator: ventilator
                });

                documentCollection.fetch().done(function () {
                    documentsView.render();
                    $('.main').removeClass('loading');
                });

                documentsView.render();
                $('.main').html(documentsView.el);
            },

            get: function (id) {
                var documentModel,
                    documentView,
                    that = this;

                documentModel = new DocumentModel({ id: id });
                documentView  = new DocumentItemView({
                    documentModel: documentModel,
                    ventilator: ventilator
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
