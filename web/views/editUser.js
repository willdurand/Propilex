App.Views.EditUser = Backbone.View.extend({
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

    this.model.save({ 
    	Firstname: this.$('[name=firstname]').val(),
    	Lastname: this.$('[name=lastname]').val(), 
    	Email: this.$('[name=email]').val(),  
    	Description: this.$('[name=description]').val(),
    	Location_id: this.$('[name=location_id]').val() 
      }, {
      success: function(model, resp) {
        new App.Views.Notice({ message: msg });

        self.model = model;
        self.render();
        self.delegateEvents();

        Backbone.history.saveLocation('users/' + model.Id);
      },
      error: function() {
        new App.Views.Error();
      }
    });

    return false;
  },

  render: function() {
    var out = '<form>';
    out += "<label for='firstname'>Prénom</label>";
    out += "<input name='firstname' type='text' />";
    
    out += "<label for='lastname'>Nom</label>";
    out += "<input name='lastname' type='text' />";
    
    out += "<label for='email'>Email</label>";
    out += "<input name='email' type='text' />";

    out += "<label for='description'>Description</label>";
    out += "<textarea name='description'>" + (this.model.escape('Description') || '') + "</textarea>";
    
    out += "<label for='location_id'>Couchage</label>";
    out += "<select name='location_id'><option value='1'>Tente</option><option value='2'>Chambre</option></select>";

    var submitText = this.model.isNew() ? 'Create' : 'Save';

    out += "<button>" + submitText + "</button>";
    out += "</form>";

    $(this.el).html(out);
    $('#appUser').html(this.el);

    this.$('[name=firstname]').val(this.model.get('Firstname'));
    this.$('[name=lastname]').val(this.model.get('Lastname'));
    this.$('[name=email]').val(this.model.get('Email'));
    this.$('[name=location_id]').val(1);
  }
});
