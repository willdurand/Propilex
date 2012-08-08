App.Controllers.Users = Backbone.Router.extend({
  routes: {
    "":                 "indexAction",
    "users/:id/edit":   "editAction",
    "users/:id/show":	"showAction",
    "users/:id/close":	"closeAction"
  },

  initialize: function () {
	  this.fetchUsers();
  },
  
  indexAction: function() {},

  editAction: function(id) {
	  App.users.each(function(user){
		  user.set({'active': false, 'editing': false});
	  });
	  App.users.get(id).set({'active': true, 'editing': true});
  },
  
  showAction: function(id) {
	  App.users.each(function(user){
		  user.set({'active': false, 'editing': false});
	  });
	  App.users.get(id).set({'active': true, 'editing': false});
  },
  
  closeAction: function(id) {
	  App.users.each(function(user){
		  user.set({'active': false, 'editing': false});
	  });
	  this.navigate('');
  },
  
  fetchUsers: function() {
	new App.Views.IndexUser({ collection: App.users });
  }
  
});
