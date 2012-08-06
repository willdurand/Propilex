App.Controllers.Users = Backbone.Router.extend({
  routes: {
    "":                 "indexAction",
    "users/new":        "newAction",
    "users/:id":        "editAction",
    "users/:id/delete": "deleteAction"
  },

  indexAction: function() {
    var users = new App.Collections.Users();
    users.fetch({
      success: function() {
        new App.Views.IndexUser({ collection: users });
      },
      error: function() {
        new Error({ message: "Error loading users." });
      }
    });
  },

  newAction: function() {
    new App.Views.EditUser({ model: new User() });
  },

  editAction: function(id) {
    var usr = new User({ id: id });
    usr.fetch({
      success: function(model, resp) {
        new App.Views.EditUser({ model: usr });
      },
      error: function() {
        new Error({ message: 'Could not find that user.' });
        window.location.hash = '#';
      }
    });
  },

  deleteAction: function(id) {
    var usr = new User({ id: id });
    usr.destroy({
      success: function(model, response) {
        new App.Views.Notice({ message: 'User successfully deleted' });
        window.location.hash = '#';
      }
    });
  }
});
