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
                $('.messages').append(this.noticeTemplate({
                    message: message
                }));
            }
        }))();
    }
);
