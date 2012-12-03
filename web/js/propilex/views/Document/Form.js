define(
    [
        'text!templates/documentForm.html',
        'jquery',
        'backbone',
        'backbone-forms',
        'garlicjs'
    ],
    function (template, $, Backbone) {
        return Backbone.View.extend({
            template: _.template(template),

            events: {
                'click .save': 'onClickSave'
            },

            initialize: function (options) {
                this.documentModel = options.documentModel;
                this.vent = options.vent;
                this.form = new Backbone.Form({
                    model: this.documentModel
                });

                if (!this.documentModel.isNew()) {
                    $('.main').addClass('loading');
                }
            },

            render: function () {
                this.form.render();

                this.$el.html(this.template({
                    isNew: this.documentModel.isNew()
                }));

                this.$el.find('form')
                    .prepend(this.form.el)
                    .garlic();
            },

            onClickSave: function (e) {
                e.preventDefault();

                var that = this,
                    errors = this.form.commit();

                if (errors) {
                    return;
                }

                this.documentModel.save(null, {
                    success: function () {
                        that.$el.find('form').garlic('destroy');
                        that.vent.trigger('document:detail', that.documentModel.get('id'));
                    }
                });
            }
        });
    }
);
