define(function (require) {
    var t = require('t'),
        $ = require('jquery');

    QUnit.module('t');

    QUnit.test('is a function', function () {
        QUnit.strictEqual(typeof t, 'function');
    });

    QUnit.test('returns the string if not in the catalog', function () {
        QUnit.strictEqual(t('should_not_exist'), 'should_not_exist');
    });

    QUnit.test('can be exposed through $', function () {
        $.t = t;

        QUnit.strictEqual($.t('foo'), 'This is foo');
    });

    QUnit.test('replaces placeholders by their values', function () {
        QUnit.strictEqual(t('bar', { name: 'Sparta' }), 'This is Sparta');
    });
});
