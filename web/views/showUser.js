App.Views.ShowUser = Backbone.View.extend({
  events: {
	  "click .setActive": "setActive",
	  "mouseenter .editEnabled": "setEditable",
	  "click .saveField": "saveField"
  },
  
  tagName: "li",

  initialize: function() {
    _.bindAll(this, 'render');
    this.model.bind('change', this.render);
    this.render();
  },

  render: function() {
	  var item = this.model;
	  out = '<a href="#users/' + item.get('Id') + '/show" class="setActive">';
	  out += '<img src="img/users/photo_' + item.get('Id') + '.jpg" alt="Photo de ' + item.getDisplayName() + '"/>';
	  out += '</a><div class="moreInformation" style="display:' + ( item.get('active') ? 'block' : 'none') + ';">';
	  out += '<span class="firstname editEnabled form-field-text">' + item.escape('Firstname') + '</span>';
	  out += ' ' + ( item.getNumber() > 0 ? 'sont des' : 'est un' ) + ' ';
	  out += '<span class="affiliation">' + item.get('Affiliation') + '</span>';

	  if (item.getNumber() > 0 ) {
	  	 out += '<span class="answer">Ils ont confirmés pour <span class="number">' + item.get('Number') + '</span> personne(s)</span>';
	  }
	  else if (item.isAnswered() == true) {
	  	 out += '<span class="answer">Ils ne pourront venir</span>';
	  }
	  else {
	  	 out += '<span class="answer">Nous sommes en attente de réponse</span>';
	  }
	  out += '<span class="description">' + item.get('Description') + '</span>';
	  out += '</div>';
	  $(this.el).html(out);
	  
	  if (item.get('active') ){
		  $(this.el).addClass('active');
	  }
	  else {
		  $(this.el).removeClass('active');
	  }
	  
	  return this;
  },
  
  setActive: function() {
	  if (this.$('.moreInformation').css('display') == 'none') {
		  this.model.set('active', true);
		  $(this.el).addClass('active');
		  this.$('.moreInformation').show();
	  }
	  else {
		  this.model.set('active', false);
		  $(this.el).removeClass('active');
		  this.$('.moreInformation').hide();
		  window.location.hash = '#';
		  return false;
	  }
  },
  
  setEditable: function(e) {
	  // @todo display associate form field and save button
	  var elem = $(e.target);
	  var elemClasses = elem.attr('class').split(' ');
	  var formFieldType = '';
	  _.each(elemClasses, function(elemClass) {
		  if (elemClass.search('form-field-') == 0 ) {
			  formFieldType = elemClass;
		  }
	  });
	  
	  switch (formFieldType) {
	    case 'form-field-text': 
	    	elem.css({'border': '1px solid #777', 'background-color': '#efefef'});
	    	break;
	    default :
	    	break;
	  }
  },
  
  saveField: function() {
	  // @todo get new value, assign to model and save it
  }
});
