define(function (require) {
    var DocumentModel = require('models/Document');

    QUnit.module('models/Document');

    QUnit.test('getCreatedAt() returns null if no created_at date', function () {
        var doc = new DocumentModel();

        QUnit.strictEqual(doc.getCreatedAt(), null);
    });

    QUnit.test('getCreatedAt() returns an instance of Moment', function () {
        var doc = new DocumentModel({ created_at: {"date":"2012-12-06 03:00:07" }});

        QUnit.strictEqual(typeof doc.getCreatedAt(), 'object');
        QUnit.ok(doc.getCreatedAt().isValid());
    });

    QUnit.test('presenter() adds humanized_date', function () {
        var doc = new DocumentModel({ id: 123, created_at: {"date":"2012-12-06 03:00:07" }}),
            data;

        data = doc.presenter();

        QUnit.ok(data);
        QUnit.ok(data.id);
        QUnit.ok(data.created_at);
        QUnit.ok(data.humanized_date);
    });

    QUnit.test('getHumanizedDate() returns empty if no created_at', function () {
        var doc = new DocumentModel();

        QUnit.strictEqual(doc.getHumanizedDate(), '');
    });
});
