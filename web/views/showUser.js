App.Views.ShowUser = Backbone.View.extend({
  events: {
	  "click .setActive": "setActive",
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
	  out += '<span class="firstname">' + item.escape('Firstname') + '</span>';
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
	  return this;
  },
  
  setActive: function() {
	  if (this.$('.moreInformation').css('display') == 'none') {
		  this.model.set('active', true);
		  this.$('.moreInformation').show();
	  }
	  else {
		  this.model.set('active', false);
		  this.$('.moreInformation').hide();
		  window.location.hash = '#';
	  }
  }
});
