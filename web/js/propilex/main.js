require({
    paths: {
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

        Backbone.history.start();
    }
);
