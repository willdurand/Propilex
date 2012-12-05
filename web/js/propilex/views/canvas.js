/*globals window: true, localStorage: true, location: true */
define(
    [
        'text!templates/canvas.html',
        'underscore',
        'jquery',
        'backbone',
        'ventilator',
        't'
    ],
    function (template, _, $, Backbone, ventilator, t) {
        "use strict";

        return new (Backbone.View.extend({
            template: _.template(template),

            noticeTemplate: _.template($(template).filter('#message-notice').html()),

            events: {
                'click .languages a': 'onSelectLanguage'
            },

            initialize: function () {
                ventilator.on('canvas:message:notice', function (message) {
                    this.addNotice(message);
                }, this);
            },

            render: function () {
                this.$el.html(this.template({
                    t: t
                }));
            },

            addNotice: function (message) {
                var $message = $(this.noticeTemplate({
                    message: message
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
            }
        }))();
    }
);
