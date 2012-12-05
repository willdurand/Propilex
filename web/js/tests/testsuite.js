(function () {
    QUnit.config.autostart = false;

    require.config({
        baseUrl: "../propilex",
    });

    var testModules = [
    ];

    require(testModules, QUnit.start);
}());
