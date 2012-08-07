App.Controllers.Users = Backbone.Router.extend({
  routes: {
    "":                 "indexAction",
    "users/:id":        "editAction",
    "users/:id/show":	"showAction"
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
  
  showAction: function(id) {
	  App.Collections.Users.get(id).set('active', true);
  }
  
});
