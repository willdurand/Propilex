define(
    [
        'moment',
        'underscore',
        'jquery',
        'backbone',
        't'
    ],
    function (moment, _, $, Backbone, t) {
        "use strict";

        return Backbone.Model.extend({
            dateFormat: 'YYYY-MM-DD HH:mm:ss',

            defaults: {
                title: '',
                body: ''
            },

            schema: {
                title: { type: 'Text', validators: [ 'required' ], title: t('form.title') },
                body:  { type: 'TextArea', validators: [ 'required' ], title: t('form.body') }
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

            presenter: function () {
                return _.extend(this.toJSON(), {
                    humanized_date: this.getHumanizedDate()
                });
            }
        });
    }
);
