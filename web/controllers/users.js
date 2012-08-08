App.Controllers.Users = Backbone.Router.extend({
  routes: {
    "":                 "indexAction",
    "users/:id":        "editAction",
    "users/:id/show":	"showAction"
  },

  initialize: function () {
	  this.fetchUsers();
  },
  
  indexAction: function() {},

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
	  App.users.each(function(user){
		  user.set('active', false);
	  });
	  App.users.get(id).set('active', true);
  },
  
  fetchUsers: function() {
	/*
	  App.users = new App.Collections.Users([
	    {"Id":1,"LocationId":1,"Firstname":"Nicolas","Lastname":"B\u00e9hier","Email":"nbd@gmail.com","Affiliation":null,"Description":"Ma Description","Photo":null,"Answered":"en attente de validation","CreatedAt":{"date":"2012-08-06 15:30:08","timezone_type":3,"timezone":"Europe\/Paris"},"UpdatedAt":{"date":"2012-08-06 15:30:08","timezone_type":3,"timezone":"Europe\/Paris"}},
	    {"Id":2,"LocationId":2,"Firstname":"Simon, V\u00e9ronique et Rose","Lastname":"Assani","Email":"simon@gmail.com","Affiliation":"Polytech Tours","Description":null,"Photo":null,"Answered":"absent","CreatedAt":{"date":"2012-08-07 07:11:40","timezone_type":3,"timezone":"Europe\/Paris"},"UpdatedAt":{"date":"2012-08-07 07:11:40","timezone_type":3,"timezone":"Europe\/Paris"}},
	    {"Id":3,"LocationId":1,"Firstname":"Flavien et Guylaine","Lastname":"Lenourichel","Email":"flavien@gmail.com","Affiliation":"Erasmus, Lyc\u00e9e","Description":null,"Photo":null,"Answered":"pr\u00e9sent","CreatedAt":{"date":"2012-08-07 07:11:40","timezone_type":3,"timezone":"Europe\/Paris"},"UpdatedAt":{"date":"2012-08-07 07:11:40","timezone_type":3,"timezone":"Europe\/Paris"}}
	]);
	*/
	new App.Views.IndexUser({ collection: App.users });
	// Problème de chargement différé : 
	// showAction ne connaît pas encore la collection, 
	// utiliser des événements pour afficher après avoir reçu les données
	/*App.users.fetch({
	  success: function() {
	    new App.Views.IndexUser({ collection: this.users });
	  },
	  error: function() {
	    new Error({ message: "Error loading users." });
	  }
	});*/
  }
  
});
