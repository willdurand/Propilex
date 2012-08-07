App.Views.IndexUser = Backbone.View.extend({
  initialize: function() {
    this.collection = this.options.collection;
    this.render();
  },

  render: function() {
    if(this.collection.models.length > 0) {
      var out = '<ul id="invites">';

      this.collection.each(function(item) {
    	new App.Views.ShowUser({model: item});
      });

      out += '</ul>';
    }
    $(this.el).html(out);
    $('#appUser').html(this.el);
  }
});
