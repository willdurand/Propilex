define(
    [
        'text!templates/documentItem.html'
    ],
    function (template) {
        return Backbone.View.extend({
            template: _.template(template),

            events: {
                'click .edit': 'onClickEdit',
                'click .delete': 'onClickDelete',
            },

            initialize: function (options) {
                this.documentModel = options.documentModel;
                this.vent = options.vent;
            },

            render: function () {
                this.$el.html(this.template(this.documentModel.toViewJSON()));
            },

            onClickEdit: function (e) {
                e.preventDefault();

                this.vent.trigger('document:edit', this.documentModel.get('id'));
            },

            onClickDelete: function (e) {
                e.preventDefault();

                var that = this;

                this.documentModel.destroy(null, {
                    success: function () {
                        console.log('trigger document:all');
                        that.vent.trigger('document:all');
                    },
                    error: function () {
                        console.log('oO');
                    }
                });
            }
        });
    }
);
