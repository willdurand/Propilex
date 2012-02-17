App.Controllers.Documents = Backbone.Router.extend({
  routes: {
    "":               "index",
    "documents/:id":  "edit",
    "new":            "create"
  },

  index: function() {
    var documents = new App.Collections.Documents();
    documents.fetch({
      success: function() {
        new App.Views.Index({ collection: documents });
      },
      error: function() {
        new Error({ message: "Error loading documents." });
      }
    });
  },

  edit: function(id) {
    var doc = new Document({ id: id });
    doc.fetch({
      success: function(model, resp) {
        new App.Views.Edit({ model: doc });
      },
      error: function() {
        new Error({ message: 'Could not find that document.' });
        window.location.hash = '#';
      }
    });
  },

  create: function() {
    new App.Views.Edit({ model: new Document() });
  }
});
