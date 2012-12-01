App.Views.Index = Backbone.View.extend({
  initialize: function() {
    this.collection = this.options.collection;
    this.render();
  },

  render: function() {
    if(this.collection.models.length > 0) {
      var out = '<div class="row"><div class="span12"><p><a class="btn" href="#new">Create New</a></p></div></div>';
      out += '<div class="row"><div class="span12"><table class="table">';

      this.collection.each(function(item) {
        out += "<tr>";
        out += "<td><a href='#documents/" + item.get('Id') + "'>" + item.escape('Title') + "</a></td>";
        out += '<td><a class="btn" href="#documents/"' + item.get('Id') + '/delete">delete</a></td>';
        out += "</tr>";
      });

      out += "</table></div></div>";
    } else {
      out = '<div class="alert"><button type="button" class="close" data-dismiss="alert">×</button>No documents!</div><a class="btn" href="#new">Create one</a>';
    }
    $(this.el).html(out);
    $('#app').html(this.el);
  }
});
