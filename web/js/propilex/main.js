require({
    paths: {
        i18n: '/js/libs/require/i18n',
        text: '/js/libs/require/text'
    }
});

require(
    [
        'router',
        'views/canvas'
    ],
    function (router, canvasView) {
        canvasView.render();
        $('body').prepend(canvasView.el);
    }
);
