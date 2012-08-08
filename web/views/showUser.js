App.Views.ShowUser = Backbone.View.extend({
  events: {
	  "click .setActive": "setActive",
	  "mouseenter .editEnabled": "showEditable",
	  "mouseleave .editEnabled": "hideEditable",

	  "click .saveField": "saveField",
	  "click .editEnabled": "setEditable"
  },
  
  tagName: "li",

  initialize: function() {
    _.bindAll(this, 'render');
    this.model.bind('change', this.render);
    this.render();
  },

  render: function() {
	  var templateData = this.model.toJSON();
	  templateData.DisplayName = this.model.getDisplayName();
	  if (templateData.Affiliation == null) { templateData.Affiliation = ''; }
	  if (this.model.getNumber() > 0 ) {
		  templateData.Answer = '<span class="answer">Ils ont confirmés</span>';
	  }
	  else if (this.model.isAnswered() == true) {
		  templateData.Answer = '<span class="answer">Ils ne pourront venir</span>';
	  }
	  else {
		  templateData.Answer = '<span class="answer">Nous sommes en attente de réponse</span>';
	  }
	  
	  var template = _.template( $("#user_show_template").html(), templateData);
	  $(this.el).html(template );
	  
	  if (this.model.get('active') ){
		  $(this.el).addClass('active');
	  }
	  else {
		  $(this.el).removeClass('active');
	  }
	  
	  return this;
  },
  
  setActive: function() {
	  if (this.$('.moreInformation').css('display') == 'none') {
		  this.model.set({'active': true});
		  $(this.el).addClass('active');
		  this.$('.moreInformation').show();
	  }
	  else {
		  this.model.set({'active': false});
		  $(this.el).removeClass('active');
		  this.$('.moreInformation').hide();
		  window.location.hash = '#';
		  return false;
	  }
  },
  
  showEditable: function(e) {
  	var elem = $(e.target);
  	if (!this.model.get('editing') ) {
  		elem.addClass('displayEdit');
  	}
  },
  
  hideEditable: function(e) {
  	var elem = $(e.target);
  	if (!this.model.get('editing') ) {
  		elem.removeClass('displayEdit');
  	}
  },

  setEditable: function(e) {
	  // @todo display associate form field and save button
	  var elem = $(e.target);
	  this.model.set({'editing': true}, {silent: true});
	  elem.removeClass('displayEdit');

	  var elemClasses = elem.attr('class').split(' ');
	  var formFieldType = '';
	  _.each(elemClasses, function(elemClass) {
		  if (elemClass.search('form-field-') == 0 ) {
			  formFieldType = elemClass;
		  }
	  });
	  
	  switch (formFieldType) {
	    case 'form-field-text': 
	    	var value = elem.text();
	    	var width = elem.width();
	    	var formField = $('<input type="text" name="' + formFieldType + '" id="' + formFieldType + '" style="width:' + width + 'px"/>').val(value);
	    	var formButton = $('<img src="/img/check_16.png" alt="sauvegarder" class="saveField"/>');
	    	elem.html(formField).append(formButton);
	    	break;
	    default :
	    	break;
	  }
  },
  
  saveField: function() {
	  // @todo get new value, assign to model and save it
	  this.model.set({'editing': false}, {silent: true});
  }
});
