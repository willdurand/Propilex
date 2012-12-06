/*globals window: true, localStorage: true, location: true */
define(
    [
        'text!templates/canvas.html',
        'underscore',
        'jquery',
        'backbone',
        'ventilator',
        't',
        'key'
    ],
    function (template, _, $, Backbone, ventilator, t, key) {
        "use strict";

        return new (Backbone.View.extend({
            template: _.template(template),

            messageTemplate: _.template($(template).filter('#message').html()),

            events: {
                'click .languages a': 'onSelectLanguage'
            },

            initialize: function () {
                ventilator.on('canvas:message:notice', function (message) {
                    this.addMessage(message, 'info');
                }, this);

                ventilator.on('canvas:message:error', function (message) {
                    this.addMessage(message, 'error');
                }, this);

                var that = this;
                key('h', function () { that.displayHelp(); });
                key('n', function () { ventilator.trigger('document:create'); });
                key('l', function () { ventilator.trigger('document:all'); });
            },

            render: function () {
                this.$el.html(this.template({
                    language: t('language'),
                    fr: t('language.fr'),
                    en: t('language.en'),
                    help: t('help'),
                    helph: t('help.h'),
                    helpl: t('help.l'),
                    helpn: t('help.n')
                }));
            },

            addMessage: function (message, level) {
                var $message = $(this.messageTemplate({
                    message: message,
                    level: level
                }));

                $('.messages').append($message);

                window.setTimeout(function() {
                    $message.remove();
                }, 3000);
            },

            onSelectLanguage: function (e) {
                e.preventDefault();

                localStorage.setItem('locale', $(e.target).data('locale'));
                location.reload();
            },

            displayHelp: function () {
                this.$('#help').modal();
            }
        }))();
    }
);
