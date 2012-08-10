var User = Backbone.Model.extend({
  idAttribute: "Id",
		
  defaults: {
    "edit": false,
    "active": false,
    "editing": false
  },
	
  url : function() {
    var base = 'users/';

    if (this.isNew()) {
      return base;
    }

    return base + (base.charAt(base.length - 1) == '/' ? '' : '/') + this.id;
  },
  
  getDisplayName: function() {
	  return this.get('Firstname') + ' ' + this.get('Lastname');
  },
  
  getNumber: function() {
	  return this.get('Firstname').search(' ');
  },
  
  isAnswered: function() {
	  return true;
  },
  
  validate: function(attributes) {
	  if (! _.isString(attributes.Firstname ) ) { console.log("not a string"); return false; }
	  if (attributes.Firstname.length == 0 ) { console.log("empty string"); return false; }
  }

});
