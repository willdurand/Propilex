define(
    [
        'text!templates/documentForm.html',
        'underscore',
        'jquery',
        'backbone',
        't',
        'backbone-forms',
        'garlicjs'
    ],
    function (template, _, $, Backbone, t) {
        "use strict";

        return Backbone.View.extend({
            template: _.template(template),

            events: {
                'click .save': 'onClickSave'
            },

            initialize: function (options) {
                this.documentModel = options.documentModel;
                this.ventilator = options.ventilator;
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
                    document: this.documentModel
                }));

                this.$el.find('form')
                    .prepend(this.form.el)
                    .garlic({ conflictManager: {
                        message: t('form.conflict')
                    }});
            },

            onClickSave: function (e) {
                e.preventDefault();

                var that = this,
                    errors = this.form.commit();

                if (errors) {
                    return;
                }

                this.documentModel.save().done(function () {
                    that.$el.find('form').garlic('destroy');

                    that.ventilator.trigger(
                        'canvas:message:notice',
                        t('message.save', { title: that.documentModel.get('title') })
                    );
                    that.ventilator.trigger('document:all');
                });
            }
        });
    }
);
