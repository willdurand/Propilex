define(function (require) {
    var t = require('t')
        $ = require('jquery');

    QUnit.module('tTest');

    QUnit.test('is a function', function () {
        QUnit.strictEqual(typeof t, 'function');
    });

    QUnit.test('returns the string if not in the catalog', function () {
        QUnit.strictEqual('should_not_exist', t('should_not_exist'));
    });

    QUnit.test('can be exposed through $', function () {
        $.t = t;

        QUnit.strictEqual('foo', $.t('foo'));
    });
});
