var User = Backbone.Model.extend({
  defaults: {
    "edit": false,
    "active": false
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
	  return this.get('Firstname').search(',');
  },
  
  isAnswered: function() {
	  return true;
  }

});
