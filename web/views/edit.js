App.Views.Edit = Backbone.View.extend({
  events: {
    "submit form": "save"
  },

  initialize: function() {
    _.bindAll(this, 'render');
    this.model.bind('change', this.render);
    this.render();
  },

  save: function() {
    var self = this;
    var msg = this.model.isNew() ? 'Successfully created!' : "Saved!";

    this.model.save({ Title: this.$('[name=title]').val(), Body: this.$('[name=body]').val() }, {
      success: function(model, resp) {
        new App.Views.Notice({ message: msg });

        self.model = model;
        self.render();
        self.delegateEvents();

        Backbone.history.saveLocation('documents/' + model.Id);
      },
      error: function() {
        new App.Views.Error();
      }
    });

    return false;
  },

  render: function() {
    var out = '<form class="form-horizontal">';
    out += '<div class="control-group"><label class="control-label" for="title">Title</label><div class="controls"><input name="title" type="text" /></div></div>';
    out += '<div class="control-group"><label class="control-label" for="body">Body</label><div class="controls">';
    out += '<textarea name="body">' + (this.model.escape('Body') || '') + "</textarea>";
    out += '</div></div>';

    var submitText = this.model.isNew() ? 'Create' : 'Save';

    out += '<div class="control-group"><div class="controls"><input type="submit" class="btn btn-primary" value="' + submitText + '" /></div></div>';
    out += "</form>";

    $(this.el).html(out);
    $('#app').html(this.el);

    this.$('[name=title]').val(this.model.get('Title')); // use val, for security reasons
  }
});
