var App = {
  Views:        {},
  Controllers:  {},
  Collections:  {},

  init: function() {
	new App.Controllers.Users();
    Backbone.history.start();
  }
};
