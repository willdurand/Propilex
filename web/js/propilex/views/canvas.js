define(
    [
        'text!templates/canvas.html',
        'underscore',
        'backbone',
        'ventilator'
    ],
    function (template, _, Backbone, ventilator) {
        return new (Backbone.View.extend({
            template: _.template(template),

            noticeTemplate: _.template($(template).filter('#message-notice').html()),

            initialize: function () {
                var that = this;

                ventilator.on('canvas:message:notice', function (message) {
                    that.addNotice(message);
                });
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
