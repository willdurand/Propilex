<?php

return (new Stack\Builder())
    ->push('Negotiation\Stack\Negotiation', $app['format.negotiator'])
    ->push('Asm89\Stack\Cors', [
        'allowedOrigins' => [ '*' ],
    ])
    ->resolve($app);
