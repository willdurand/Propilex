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
                title: { type: 'Text', validators: [ { type: 'required', message: t('form.required') } ], title: t('form.title') },
                body:  { type: 'TextArea', validators: [ { type: 'required', message: t('form.required') } ], title: t('form.body') }
            },

            url : function() {
                var base = $('body').data('api-url') + '/documents';

                return this.isNew() ? base : [ base, this.id ].join('/');
            },

            parse: function (responseObject) {
                if (responseObject.document) {
                    return responseObject.document;
                }

                return responseObject;
            },

            getHumanizedDate: function () {
                if (null !== this.getCreatedAt()) {
                    return this.getCreatedAt().fromNow();
                }

                return '';
            },

            presenter: function () {
                return _.extend(this.toJSON(), {
                    humanized_date: this.getHumanizedDate()
                });
            },

            getCreatedAt: function () {
                if (undefined !== this.get('created_at')) {
                    return moment(this.get('created_at').date, this.dateFormat);
                }

                return null;
            }
        });
    }
);
