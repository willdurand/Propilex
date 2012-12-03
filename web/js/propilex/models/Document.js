define(
    function (require) {
        return Backbone.Model.extend({
            dateFormat: 'YYYY-MM-DD HH:mm:ss',

            defaults: {
                title: '',
                body: ''
            },

            schema: {
                title: { type: 'Text', validators: [ 'required' ] },
                body:  { type: 'Text', validators: [ 'required' ] }
            },

            url : function() {
                var base = $('body').data('api-url') + '/documents/';

                return this.isNew() ? base : base + this.id;
            },

            parse: function (responseObject) {
                if (responseObject.document) {
                    return responseObject.document;
                }

                return responseObject;
            },

            getHumanizedDate: function () {
                if (undefined !== this.get('created_at')) {
                    return moment(this.get('created_at').date, this.dateFormat).fromNow();
                }

                return '';
            },

            toViewJSON: function () {
                return _.extend(this.toJSON(), {
                    humanized_date: this.getHumanizedDate()
                });
            }
        });
    }
);
