App.Views.IndexUser = Backbone.View.extend({
  initialize: function() {
    this.collection = this.options.collection;
    this.render();
  },

  render: function() {
    if(this.collection.models.length > 0) {
      var out = "<h3><a href='#users/new'>Create New</a></h3><ul>";

      this.collection.each(function(item) {
        out += "<li>";
        out += "<a href='#users/" + item.get('Id') + "'>" + item.escape('Firstname') + " " + item.escape('Lastname') + "</a>";
        out += " [<a href='#users/" + item.get('Id') + "/delete'>delete</a>]";
        out += "</li>";
      });

      out += "</ul>";
    } else {
      out = "<h3>No Users! <a href='#users/new'>Create one</a></h3>";
    }
    $(this.el).html(out);
    $('#appUser').html(this.el);
  }
});
