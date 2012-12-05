/*global window */
define(
    [
        'text!templates/canvas.html',
        'underscore',
        'jquery',
        'backbone',
        'ventilator'
    ],
    function (template, _, $, Backbone, ventilator) {
        "use strict";

        return new (Backbone.View.extend({
            template: _.template(template),

            noticeTemplate: _.template($(template).filter('#message-notice').html()),

            initialize: function () {
                ventilator.on('canvas:message:notice', function (message) {
                    this.addNotice(message);
                }, this);
            },

            render: function () {
                this.$el.html(this.template);
            },

            addNotice: function (message) {
                var $message = $(this.noticeTemplate({
                    message: message
                }));

                $('.messages').append($message);

                window.setTimeout(function() {
                    $message.remove();
                }, 3000);
            }
        }))();
    }
);
