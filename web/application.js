var App = {
  Views:        {},
  Controllers:  {},
  Collections:  {},

  init: function() {
	new App.Controllers.Users();
    //new App.Controllers.Documents();
    Backbone.history.start();
  }
};
