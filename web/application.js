_.extend(Backbone.Model.prototype, Backbone.Validation.mixin);

var App = {
  Views:        {},
  Controllers:  {},
  Collections:  {},

  init: function() {
	new App.Controllers.Users();
    Backbone.history.start();
  }
};
